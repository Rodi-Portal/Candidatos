<?php
// app/Http/Controllers/SolicitudController.php
namespace App\Http\Controllers;

use App\Models\Requisicion;
use App\Models\RequisicionIntake;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequisicionController extends Controller
{

    public function create(Request $request)
    {
        $rawToken = $request->query('token');
        \Log::info('[LV] token sha', ['sha' => $rawToken ? substr(hash('sha256', $rawToken), 0, 16) : null]);

        // payload crudo sin verificar
        [$h, $p, $s] = explode('.', $rawToken);
        $pl          = json_decode(base64_decode(strtr($p, '-_', '+/')), true);
        \Log::info('[LV] raw payload', ['idCliente' => $pl['idCliente'] ?? null, 'jti' => $pl['jti'] ?? null]);

        // 1) Token obligatorio
        $rawToken = $request->query('token');
        if (! $rawToken) {
            abort(403, 'Token no proporcionado.');
        }

        // Fix por si '+' llegó como espacio
        $token = (strpos($rawToken, ' ') !== false && strpos($rawToken, '+') === false)
        ? strtr($rawToken, ' ', '+')
        : $rawToken;

        // 2) Clave pública (PEM) para RS256
        $publicKeyPath = config('jwt.public_key');
        if (! is_file($publicKeyPath)) {
            abort(500, 'Clave pública no encontrada.');
        }
        $publicKey = file_get_contents($publicKeyPath);
        if (! $publicKey) {
            abort(500, 'Error al leer clave pública.');
        }

        // 3) Decodificar/verificar JWT
        try {
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
        } catch (\Throwable $e) {
            abort(401, 'Token inválido o expirado.');
        }

        // 4) Extraer datos
        $cliente       = $decoded->NombrePortal ?? null;
        $idCliente     = $decoded->idCliente ?? null;
        $id_usuario    = $decoded->idUsuario ?? null;
        $id_portal     = $decoded->idPortal ?? null;
        $logo          = $decoded->logo ?? 'portal_icon.png';
        $avisoToken    = isset($decoded->aviso) ? basename($decoded->aviso) : null;
        $terminosToken = isset($decoded->terminos) ? basename($decoded->terminos) : null;

        // 5) Directorio base de PDFs
        $base = '';
        foreach ([
            env('PRIVACY_PATH_PROD'),
            env('PRIVACY_PATH_SAND'),
            env('PRIVACY_PATH_LOCAL'),
        ] as $cand) {
            if ($cand && is_dir($cand)) {
                $base = rtrim($cand, "\\/") . DIRECTORY_SEPARATOR;
                break;
            }
        }
        if (empty($base)) {
            \Log::error('[LV] Ninguna ruta PRIVACY_PATH_* existe', [
                'prod'  => env('PRIVACY_PATH_PROD'),
                'sand'  => env('PRIVACY_PATH_SAND'),
                'local' => env('PRIVACY_PATH_LOCAL'),
            ]);
            abort(500, 'Ruta de documentos no disponible.');
        }

        // 6) Resolver archivos (preferir los del token; si no, defaults)
        $AVISO_DEFAULT    = 'AV_TL_V1.pdf';
        $TERMINOS_DEFAULT = 'TM_TL_V1.pdf';

        $resolver = function (?string $nombre, string $fallback) use ($base) {
            $nombre    = ($nombre && str_ends_with(strtolower($nombre), '.pdf')) ? $nombre : null;
            $candidato = $nombre ? ($base . $nombre) : null;

            if ($candidato && is_file($candidato)) {
                return [$nombre, $candidato];
            }
            $fb = $base . $fallback;
            return [$fallback, is_file($fb) ? $fb : null];
        };

        [$avisoFile, $avisoPath]       = $resolver($avisoToken, $AVISO_DEFAULT);
        [$terminosFile, $terminosPath] = $resolver($terminosToken, $TERMINOS_DEFAULT);

        // 7) URLs y hashes para auditar aceptación
        $avisoUrl     = url('aviso/' . rawurlencode($avisoFile));
        $terminosUrl  = url('terminos/' . rawurlencode($terminosFile));
        $avisoHash    = $avisoPath && is_file($avisoPath) ? hash_file('sha256', $avisoPath) : null;
        $terminosHash = $terminosPath && is_file($terminosPath) ? hash_file('sha256', $terminosPath) : null;

        // 8) Guardar en sesión para usar en store()
        session([
            'aviso_file'       => $avisoFile,
            'aviso_hash'       => $avisoHash,
            'terminos_file'    => $terminosFile,
            'terminos_hash'    => $terminosHash,
            'logo'             => $logo,
            'cliente'          => $cliente,
            'id_portal_token'  => $id_portal,
            'id_usuario_token' => $id_usuario,
            'id_cliente_token' => $idCliente,
        ]);

        // 9) Render con idCliente en la vista
        return view('clientes.requisicion', compact(
            'avisoUrl', 'terminosUrl', 'logo', 'cliente', 'avisoHash', 'terminosHash', 'idCliente'
        ));
    }

public function store(Request $request)
{
    Log::info('🟢 [store] hit', [
        'url'      => $request->fullUrl(),
        'has_file' => $request->hasFile('archivo'),
    ]);
    Log::info('🟢 [store] keys', ['keys' => array_keys($request->all())]);

    // token para reabrir el form si hay error
    $returnToken = $request->input('_from_token');

    // --- Reglas base ---
    $rules = [
        'nombre_cliente'      => ['required','string','max:120'],
        'email'               => ['required','email','max:150'],
        'telefono'            => ['required','string','max:30'],
        'metodo_comunicacion' => ['required','string','max:50'],

        'razon_social'        => ['nullable','string','max:150'],
        'nit'                 => ['nullable','string','max:50'],
        'pais_empresa'        => ['required','string','max:80'],
        'pais_otro'           => ['nullable','string','max:80'],
        'actividad'           => ['nullable','string','max:2000'],
        'sitio_web'           => ['nullable','url','max:200'],
        'fecha_solicitud'     => ['required','date'],

        'plan'                => ['required','string','max:50'],
        'requiere_voip'       => ['required','in:si,no'],
        'voip_propiedad'      => ['nullable','in:propio,asistente_virtual_ok'],
        'voip_pais_ciudad'    => ['nullable','string','max:120'],

        'miembro_bni'         => ['required','in:si,no'],
        'referido'            => ['nullable','string','max:150'],

        'horario'             => ['nullable','string','max:150'],
        'sexo_preferencia'    => ['required','string','max:20'],
        'rango_edad'          => ['nullable','string','max:50'],
        'funciones'           => ['nullable','string','max:3000'],
        'requisitos'          => ['nullable','string','max:3000'],
        'recursos'            => ['nullable','string','max:3000'],
        'usa_crm'             => ['required','in:si,no'],
        'crm_nombre'          => ['nullable','string','max:120'],

        'fecha_inicio'        => ['required','date'],
        'observaciones'       => ['nullable','string','max:3000'],

        'archivo'             => ['nullable','file','max:5120'], // 5MB

        'acepta_terminos'     => ['accepted'],
        'terminos_file'       => ['nullable','string','max:255'],
        'terminos_hash'       => ['nullable','string','max:128'],
    ];

    $validated = $request->validate($rules);
    Log::info('🟢 [store] validated keys', ['keys' => array_keys($validated)]);

    // --- Reglas condicionales ---
    if ($request->input('pais_empresa') === 'Otro') {
        $request->validate(['pais_otro' => ['required','string','max:80']]);
    }
    if ($request->input('requiere_voip') === 'si') {
        $request->validate([
            'voip_propiedad'   => ['required','in:propio,asistente_virtual_ok'],
            'voip_pais_ciudad' => ['required','string','max:120'],
        ]);
    }
    if ($request->input('usa_crm') === 'si') {
        $request->validate(['crm_nombre' => ['required','string','max:120']]);
    }

    // IDs desde sesión (seteados en create() al decodificar el token)
    $idPortal   = (int) session('id_portal_token');
    $idUsuario  = (int) session('id_usuario_token');
    $idCliente  = (int) session('id_cliente_token');
    Log::info('🟢 [store] session ids', compact('idPortal','idUsuario','idCliente'));

    if (!$idPortal || !$idCliente) {
        return redirect()
            ->to(route('solicitudes.create', ['token' => $returnToken]))
            ->withErrors('No se pudo identificar portal o cliente. Vuelve a abrir el link.')
            ->withInput();
    }

    // --- Manejo del archivo ---
    $archivoPath = null;          // ruta absoluta (si decides almacenarla)
    $nombreArchivo = null;        // nombre físico (recomendado guardar solo esto)

    if ($request->hasFile('archivo')) {
        $archivo = $request->file('archivo');

        Log::info('🟢 [store] archivo detectado', [
            'original_name' => $archivo->getClientOriginalName(),
            'size'          => $archivo->getSize(),
            'mime'          => $archivo->getClientMimeType(),
        ]);

        // Detecta entorno real
        $env = app()->environment(); // 'production', 'local', etc.
        if (app()->environment('production')) {
            $destino = env('REQ_PATH_PROD');
        } elseif ($env === 'sand' || app()->environment('sandbox')) {
            $destino = env('REQ_PATH_SAND');
        } else {
            $destino = env('REQ_PATH_LOCAL');
        }
        Log::info('🟢 [store] usando carpeta destino', ['env' => $env, 'destino' => $destino]);

        if (!is_dir($destino)) {
            Log::error('❌ Carpeta destino no existe', ['destino' => $destino]);
        } elseif (!is_writable($destino)) {
            Log::error('❌ Carpeta destino sin permisos de escritura', ['destino' => $destino]);
        }

        $destino = rtrim($destino, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $nombreArchivo = time() . '_' . $idCliente . '_' . $archivo->getClientOriginalName();

        try {
            $archivo->move($destino, $nombreArchivo);
            $full = $destino . $nombreArchivo;

            if (file_exists($full)) {
                $archivoPath = $full;
                Log::info('✅ Archivo guardado', ['path' => $archivoPath]);
            } else {
                Log::error('❌ Archivo NO guardado post-move', ['path' => $full]);
            }
        } catch (\Throwable $e) {
            Log::error('❌ Error al mover archivo', ['msg' => $e->getMessage(), 'line' => $e->getLine()]);
        }
    }

    // Extras JSON (todo lo que no esté en $rules)
    $known   = array_keys($rules);
    $known[] = '_token';
    $known[] = '_from_token';
    $known[] = 'archivo';
    $extras = collect($request->all())->except($known)->toArray();

    try {
        DB::beginTransaction();

        // 1) Guardar intake
        $intake = RequisicionIntake::create([
            'id_portal'           => $idPortal,
            'id_usuario_token'    => $idUsuario,
            'id_cliente'          => $idCliente,

            'nombre_cliente'      => $request->input('nombre_cliente'),
            'email'               => $request->input('email'),
            'telefono'            => $request->input('telefono'),
            'metodo_comunicacion' => $request->input('metodo_comunicacion'),

            'razon_social'        => $request->input('razon_social'),
            'nit'                 => $request->input('nit'),
            'pais_empresa'        => $request->input('pais_empresa'),
            'pais_otro'           => $request->input('pais_otro'),
            'actividad'           => $request->input('actividad'),
            'sitio_web'           => $request->input('sitio_web'),
            'fecha_solicitud'     => $request->input('fecha_solicitud'),

            'plan'                => $request->input('plan'),
            'requiere_voip'       => $request->input('requiere_voip'),
            'voip_propiedad'      => $request->input('voip_propiedad'),
            'voip_pais_ciudad'    => $request->input('voip_pais_ciudad'),

            'miembro_bni'         => $request->input('miembro_bni'),
            'referido'            => $request->input('referido'),

            'horario'             => $request->input('horario'),
            'sexo_preferencia'    => $request->input('sexo_preferencia'),
            'rango_edad'          => $request->input('rango_edad'),

            'funciones'           => $request->input('funciones'),
            'requisitos'          => $request->input('requisitos'),
            'recursos'            => $request->input('recursos'),

            'usa_crm'             => $request->input('usa_crm'),
            'crm_nombre'          => $request->input('crm_nombre'),

            'fecha_inicio'        => $request->input('fecha_inicio'),
            'observaciones'       => $request->input('observaciones'),

            // guarda solo nombre físico (recomendado)
            'archivo_path'        => $nombreArchivo,      // ó $archivoPath si quieres ruta completa

            'acepta_terminos'     => $request->boolean('acepta_terminos'),
            'terminos_file'       => $request->input('terminos_file', session('terminos_file')),
            'terminos_hash'       => $request->input('terminos_hash', session('terminos_hash')),

            'extras'              => empty($extras) ? null : $extras,

            'creacion'            => now(),
            'edicion'             => now(),
        ]);

        // 2) Crear requisición enlazada
        $req = Requisicion::create([
            'id_portal'          => $idPortal,
            'id_usuario'         => $idUsuario ?: null,
            'id_usuario_cliente' => null,
            'id_cliente'         => $idCliente,
            'tipo'               => 'SOLICITUD',
            'puesto'             => $request->input('plan', 'N/D'),
            'numero_vacantes'    => 1,
            'zona_trabajo'       => $request->input('pais_empresa'),
            'sueldo'             => 'N/D',
            'tipo_pago_sueldo'   => 'N/D',
            'tipo_prestaciones'  => 'N/D',
            'experiencia'        => $request->input('funciones'),
            'status'             => 1,
            'eliminado'          => 0,
            'id_intake'          => $intake->id,
        ]);

        DB::commit();

        Log::info('✅ guardado OK', ['id_intake' => $intake->id, 'req_id' => $req->id]);

        return redirect()
            ->route('registro.graciasReq')
            ->with('mensaje', 'Su vacante se registró exitosamente. Regrese al apartado de requisiciones para consultar cuando se carguen aspirantes y dar seguimiento.');

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('❌ store error', ['msg' => $e->getMessage(), 'line' => $e->getLine()]);
        return redirect()
            ->to(route('solicitudes.create', ['token' => $returnToken]))
            ->withErrors('No se pudo guardar la solicitud: ' . $e->getMessage())
            ->withInput();
    }
}


}
