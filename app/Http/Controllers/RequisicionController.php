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

        // 5) Directorio de documentos legales dentro de TalentSafe
        $storageRoot = config('paths.storage_root');

        if (! is_string($storageRoot) || trim($storageRoot) === '') {
            throw new \RuntimeException(
                'TALENTSAFE_STORAGE_PATH no está configurado.'
            );
        }

        $storageRoot = rtrim(
            str_replace('\\', '/', $storageRoot),
            '/'
        );

        $base = $storageRoot . '/default/documentos/';

        if (! is_dir($base)) {
            \Log::error('[LV] Directorio legal de TalentSafe no existe', [
                'path' => $base,
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
            'id_portal'        => $id_portal,
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
            'url'        => $request->fullUrl(),
            'has_file'   => $request->hasFile('archivo'),
            'method'     => $request->method(),
            'session_id' => session()->getId(),
        ]);
        Log::info('🟢 [store] keys', ['keys' => array_keys($request->all())]);

        $returnToken = $request->input('_from_token');

        // Validación base
        $rules     = [/* (las mismas reglas que ya tienes) */];
        $validated = $request->validate($rules);
        Log::info('🟢 [store] validated keys', ['keys' => array_keys($validated)]);

        // Reglas condicionales (igual que ya tienes) ...

        // 🔎 Traza: IDs de sesión (AQUÍ MISMO)
        $idPortal  = (int) session('id_portal_token');
        $idUsuario = (int) session('id_usuario_token');
        $idCliente = session()->has('id_cliente_token')
        ? (int) session('id_cliente_token')
        : null;
        Log::info('🟢 [store] session ids', compact('idPortal', 'idUsuario', 'idCliente'));

        if (! $idPortal) {
            Log::warning('⚠️ [store] faltan ids de sesión', compact('idPortal'));
            return redirect()
                ->to(route('solicitudes.create', ['token' => $returnToken]))
                ->withErrors('No se pudo identificar portal. Vuelve a abrir el link.')
                ->withInput();
        }

        // --- Preparar archivo ---
        // Se valida aquí, pero se mueve después de crear la requisición.
        $archivo       = null;
        $nombreArchivo = null;
        $archivoFisico = null;

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');

            Log::info('🟢 [store] archivo detectado', [
                'original_name' => $archivo->getClientOriginalName(),
                'size'          => $archivo->getSize(),
                'mime'          => $archivo->getClientMimeType(),
                'is_valid'      => $archivo->isValid(),
                'error_code'    => $archivo->getError(),
            ]);

            if (! $archivo->isValid()) {
                Log::error('❌ Archivo inválido', [
                    'error_code' => $archivo->getError(),
                ]);

                return redirect()
                    ->to(route('solicitudes.create', [
                        'token' => $returnToken,
                    ]))
                    ->withErrors('El archivo adjunto no es válido.')
                    ->withInput();
            }

            $original = basename(
                str_replace(
                    '\\',
                    '/',
                    $archivo->getClientOriginalName()
                )
            );

            $nombreArchivo = time()
                . '_'
                . $idPortal
                . '_'
                . $original;
        }
        // Extras JSON
        $known   = array_keys($rules);
        $known[] = '_token';
        $known[] = '_from_token';
        $known[] = 'archivo';
        $extras  = collect($request->all())->except($known)->toArray();

        try {
            DB::beginTransaction();

            // 1) Intake
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
                // Guarda NOMBRE físico (recomendado). Si prefieres ruta, usa $archivoPath
                'archivo_path'        => null,
                'acepta_terminos'     => $request->boolean('acepta_terminos'),
                'terminos_file'       => $request->input('terminos_file', session('terminos_file')),
                'terminos_hash'       => $request->input('terminos_hash', session('terminos_hash')),
                'extras'              => empty($extras) ? null : $extras,
                'creacion'            => now(),
                'edicion'             => now(),
            ]);

            // 2) Requisición ligada
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

            /*
             * Guardar documento en TalentSafe.
             *
             * BD:
             *   archivo_path = solo filename.
             *
             * Físico:
             *   portales/{portal}/clientes/{cliente}/
             *   requisiciones/{req}/documentos/{archivo}
             */
            if ($archivo !== null && $nombreArchivo !== null) {
                if ($idCliente <= 0) {
                    throw new \RuntimeException(
                        'No se pudo identificar el cliente de la requisición.'
                    );
                }

                $storageRoot = config('paths.storage_root');

                if (
                    ! is_string($storageRoot)
                    || trim($storageRoot) === ''
                ) {
                    throw new \RuntimeException(
                        'TALENTSAFE_STORAGE_PATH no está configurado.'
                    );
                }

                $storageRoot = rtrim(
                    str_replace('\\', '/', $storageRoot),
                    '/'
                );

                $destino = $storageRoot
                    . '/portales/'
                    . $idPortal
                    . '/clientes/'
                    . $idCliente
                    . '/requisiciones/'
                    . $req->id
                    . '/documentos';

                if (
                    ! is_dir($destino)
                    && ! mkdir($destino, 0775, true)
                    && ! is_dir($destino)
                ) {
                    throw new \RuntimeException(
                        'No se pudo crear directorio: '
                        . $destino
                    );
                }

                if (! is_writable($destino)) {
                    throw new \RuntimeException(
                        'Directorio sin permisos de escritura: '
                        . $destino
                    );
                }

                Log::info('🟢 [store] destino TalentSafe', [
                    'portal'      => $idPortal,
                    'cliente'     => $idCliente,
                    'requisicion' => $req->id,
                    'destino'     => $destino,
                ]);

                $archivo->move(
                    $destino,
                    $nombreArchivo
                );

                $archivoFisico = $destino
                    . '/'
                    . $nombreArchivo;

                if (! is_file($archivoFisico)) {
                    throw new \RuntimeException(
                        'Archivo no encontrado después del move: '
                        . $archivoFisico
                    );
                }

                /*
                 * Solamente filename en BD.
                 */
                $intake->archivo_path = $nombreArchivo;
                $intake->edicion      = now();
                $intake->save();

                Log::info('✅ Archivo de requisición guardado', [
                    'path' => $archivoFisico,
                    'name' => $nombreArchivo,
                ]);
            }
            DB::commit();
            Log::info('✅ guardado OK', ['id_intake' => $intake->id, 'req_id' => $req->id]);

            return redirect()
                ->route('registro.graciasReq')
                ->with('mensaje', 'Su vacante se registró exitosamente. Regrese al apartado de requisiciones para dar seguimiento.');
        } catch (\Throwable $e) {
            DB::rollBack();
            /*
             * El filesystem no participa en la transacción SQL.
             */
            if (
                ! empty($archivoFisico)
                && is_file($archivoFisico)
            ) {
                @unlink($archivoFisico);
            }
            Log::error('❌ store error', ['msg' => $e->getMessage(), 'line' => $e->getLine()]);
            return redirect()
                ->to(route('solicitudes.create', ['token' => $returnToken]))
                ->withErrors('No se pudo guardar la solicitud: ' . $e->getMessage())
                ->withInput();
        }
    }

}
