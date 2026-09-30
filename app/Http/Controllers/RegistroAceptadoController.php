<?php
namespace App\Http\Controllers;

use App\Models\LinkEmpleado;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

// para crear carpetas y mover archivos

class RegistroAceptadoController extends Controller
{

    // ← modelo Eloquent de links_empleados

    public function create(Request $request)
    {
        // ===== Defaults si no hay token o es inválido =====
        $id_empleado = null;
        $idPortal    = null;
        $logo        = 'portal_icon.png';

        $rawToken = $request->query('token');

        // Si no viene token, manda a vista de revocado / inválido
        if (empty($rawToken)) {
            \Log::info('create(): sin token en query');
            return response()->view('registro.revocado', [
                'motivo' => 'Enlace inválido o incompleto.',
                'logo'   => $logo,
            ], 410);
        }

        // Normaliza por si '+' llegó como espacio
        $token = (strpos($rawToken, ' ') !== false && strpos($rawToken, '+') === false)
        ? strtr($rawToken, ' ', '+')
        : $rawToken;

        // Clave pública (ruta o PEM)
        $pub = config('jwt.public_key');
        if (! $pub) {
            \Log::warning('create(): jwt.public_key no configurada');
            return response()->view('registro.revocado', [
                'motivo' => 'Configuración de seguridad no disponible.',
                'logo'   => $logo,
            ], 410);
        }

        $publicKey = is_file($pub) ? @file_get_contents($pub) : $pub;
        if (! $publicKey) {
            \Log::warning('create(): no se pudo cargar la clave pública');
            return response()->view('registro.revocado', [
                'motivo' => 'No se pudo verificar el enlace.',
                'logo'   => $logo,
            ], 410);
        }

        try {
            // Decodifica/valida firma y exp del JWT (lanza excepción si expira o es inválido)
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
            $c       = (array) $decoded;

            // Campos firmados por tu backend (CodeIgniter)
            $id_empleado = $c['idEmpleado'] ?? null;
            $idPortal    = $c['idPortal'] ?? ($c['idCliente'] ?? null); // compatibilidad
            $logo        = $c['logo'] ?? $logo;

            // Identificadores del token
            $jti   = $c['jti'] ?? null;
            $sha16 = substr(hash('sha256', $token), 0, 16);

            // Guarda en sesión para usar en store() y para revocar
            session([
                'id_empleado' => $id_empleado,
                'id_portal'   => $idPortal,
                'logo'        => $logo,
                'token_jti'   => $jti,
                'token_sha16' => $sha16,
            ]);

            // ===== Validación en tabla links_empleados =====
            if (! $id_empleado) {
                \Log::warning('create(): JWT sin idEmpleado');
                return response()->view('registro.revocado', [
                    'motivo' => 'Enlace inválido (faltan datos).',
                    'logo'   => $logo,
                ], 410);
            }

            // Busca el registro del link asociado al empleado y al token (por jti o huella)
            $q = LinkEmpleado::where('id_empleado', $id_empleado)
                ->where(function ($qq) use ($jti, $sha16) {
                    if (! empty($jti)) {
                        $qq->where('jti', $jti);
                    } else {
                        $qq->where('token_sha16', $sha16);
                    }
                })
                ->where('eliminado', 0); // <- NO eliminado

            $rec = $q->first();

            if (! $rec) {
                // No existe o fue eliminado
                \Log::info('create(): link no encontrado o eliminado', ['id_empleado' => $id_empleado, 'jti' => $jti, 'sha16' => $sha16]);
                return response()->view('token_expirado', [
                    'motivo' => 'El enlace no existe o fue eliminado.',
                    'logo'   => $logo,
                ], 410);
            }

            // Expirado por campo de BD (además del exp del JWT ya validado arriba)
            $expirado = $rec->exp_unix && $rec->exp_unix < time();

            if (
                (int) ($rec->is_used ?? 0) === 1 || // ya fue usado
                ! empty($rec->revoked_at) ||         // revocado manualmente
                $expirado ||                        // vencido por exp_unix
                (int) ($rec->eliminado ?? 0) === 1  // eliminado (defensa doble)
            ) {
                return response()->view('token_expirado', [
                    'motivo' => 'El enlace ya fue utilizado, eliminado o ha expirado.',
                    'logo'   => $logo,
                ], 410);
            }

                                    // ===== OK: acceso permitido =====
            $idCliente = $idPortal; // compatibilidad con tu blade
            return view('nuevoIngreso', compact('logo', 'idCliente', 'id_empleado'));

        } catch (\Throwable $e) {
            // Incluye expirado por 'exp' del JWT o firma inválida
            \Log::warning('JWT inválido/expirado en create()', ['msg' => $e->getMessage()]);
            return response()->view('registro.revocado', [
                'motivo' => 'El enlace es inválido o ha expirado.',
                'logo'   => $logo,
            ], 410);
        }
    }

    public function store(Request $request)
    {
        // ===== LOGS DE ENTRADA Y ARCHIVOS =====
        \Log::info('🟢 [NuevoIngreso] inicio store', [
            'keys'           => array_keys($request->all()),
            'session_id'     => session()->getId(),
            'id_empleado'    => session('id_empleado'),
            'id_portal'      => session('id_portal'),
            'content_type'   => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),
        ]);

        // Log de archivos detectados por Laravel y por PHP nativo
        $allFiles = [];
        foreach ($request->allFiles() as $k => $v) {
            $allFiles[$k] = is_array($v) ? 'array' : (is_object($v) ? get_class($v) : gettype($v));
        }
        \Log::info('🟢 [NuevoIngreso] allFiles()', $allFiles);
        \Log::info('🟢 [NuevoIngreso] $_FILES', $_FILES);

        // ===== CONTEXTO (del token decodificado en create()) =====
        $idEmpleado = session('id_empleado') ?? $request->input('id_empleado');
        $idPortal   = session('id_portal') ?? $request->input('id_portal');

        if (! $idEmpleado || ! $idPortal) {
            return back()
                ->withErrors(['token' => 'El enlace es inválido o ha expirado. Vuelve a abrir el formulario desde el enlace.'])
                ->withInput();
        }

        // ===== VALIDACIÓN =====
        $rules = [
            'nombre_completo'                 => 'required|string|max:255',

            'metodo_pago'                     => 'required|in:bank,binance,zelle,zinli',
            'bank_details'                    => 'required_if:metodo_pago,bank|nullable|string',

            'binance_email'                   => 'required_if:metodo_pago,binance|nullable|email',
            'zelle_email'                     => 'required_if:metodo_pago,zelle|nullable|email',
            'zelle_titular'                   => 'required_if:metodo_pago,zelle|nullable|string|max:255',
            'zelle_phone'                     => 'required_if:metodo_pago,zelle|nullable|string|max:30',

            'contacto_principal'              => 'required|string',
            'contacto_principal_propietario'  => 'required|string|max:255',
            'contacto_secundario'             => 'required|string',
            'contacto_secundario_propietario' => 'required|string|max:255',
            'contacto_adicional'              => 'required|string',
            'contacto_adicional_propietario'  => 'required|string|max:255',

            // Archivos
            'cedula_identidad'                => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'foto_asociado'                   => 'required|image|max:5120',
            'acuerdo_confidencialidad'        => 'required|file|mimes:pdf,doc,docx|max:10240',
            'convenio_confidencialidad'       => 'required|file|mimes:pdf,doc,docx|max:10240',
        ];

        $messages = [
            'required'    => 'Este campo es obligatorio.',
            'required_if' => 'Este campo es obligatorio para el método seleccionado.',
            'email'       => 'Ingrese un correo válido.',
            'mimes'       => 'Tipo de archivo no permitido.',
            'max'         => 'El archivo excede el tamaño máximo permitido.',
        ];

        $validated = $request->validate($rules, $messages);

        // ===== RUTA FÍSICA DE DESTINO =====
        // El id_cliente real se obtiene del empleado; nunca se asume
        // que id_portal e id_cliente sean equivalentes.
        $empleadoStorage = \DB::table('empleados')
            ->select('id_cliente', 'id_portal')
            ->where('id', (int) $idEmpleado)
            ->first();

        if (! $empleadoStorage) {
            return back()
                ->withErrors(['general' => 'No se encontró el empleado asociado al enlace.'])
                ->withInput();
        }

        if ((int) $empleadoStorage->id_portal !== (int) $idPortal) {
            \Log::warning('⚠️ [NuevoIngreso] portal no corresponde al empleado', [
                'id_empleado'     => (int) $idEmpleado,
                'portal_token'    => (int) $idPortal,
                'portal_empleado' => (int) $empleadoStorage->id_portal,
            ]);

            return back()
                ->withErrors(['general' => 'El empleado no corresponde al portal indicado.'])
                ->withInput();
        }

        $idCliente = (int) $empleadoStorage->id_cliente;

        if (! $idCliente) {
            return back()
                ->withErrors(['general' => 'El empleado no tiene cliente asociado.'])
                ->withInput();
        }

        $docsRoot = (string) config('paths.storage_root');

        if ($docsRoot === '') {
            \Log::error('❌ [NuevoIngreso] TALENTSAFE_STORAGE_PATH no configurado');

            return back()
                ->withErrors(['general' => 'La ruta de almacenamiento no está configurada.'])
                ->withInput();
        }

        $relativeDir = sprintf(
            'portales/%d/_documentEmpleado/clientes/%d/empleados/%d',
            (int) $idPortal,
            $idCliente,
            (int) $idEmpleado
        );

        $destBase = rtrim($docsRoot, '/\\')
            . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $relativeDir);

        \Log::info('🟢 [NuevoIngreso] destino docs', [
            'env'          => app()->environment(),
            'docsRoot'     => $docsRoot,
            'destBase'     => $destBase,
            'rootExists'   => file_exists($docsRoot),
            'rootWritable' => is_writable($docsRoot),
        ]);

        if (! \Illuminate\Support\Facades\File::isDirectory($destBase)) {
            \Illuminate\Support\Facades\File::makeDirectory($destBase, 0775, true);
        }
        \Log::info('🟢 [NuevoIngreso] destBase status', [
            'exists'   => \Illuminate\Support\Facades\File::isDirectory($destBase),
            'writable' => is_writable($destBase),
        ]);

        // Mapa de inputs => nombre personalizado (humano)
        $labelByKey = [
            'cedula_identidad'          => 'Cédula de identidad',
            'foto_asociado'             => 'Foto Asociado',
            'acuerdo_confidencialidad'  => 'Acuerdo de Confidencialidad',
            'convenio_confidencialidad' => 'Convenio de Confidencialidad',
        ];

        // Campos de texto que se guardan COMO EXTRAS (SOLO TEXTO)
        $extrasTexto = [
            'nombre_completo',
            'metodo_pago',
            'bank_details',
            'binance_email',
            'zelle_email',
            'zelle_titular',
            'zelle_phone',
            'contacto_principal',
            'contacto_principal_propietario',
            'contacto_secundario',
            'contacto_secundario_propietario',
            'contacto_adicional',
            'contacto_adicional_propietario',
        ];

        try {
            \DB::transaction(function () use ($request, $validated, $extrasTexto, $destBase, $relativeDir, $labelByKey, $idEmpleado) {
                // 1) Guardar EXTRAS (texto)
                foreach ($extrasTexto as $campo) {
                    $valor = $validated[$campo] ?? $request->input($campo);
                    if ($valor === null || $valor === '') {
                        continue;
                    }

                    \App\Models\CampoExtraEmpleado::updateOrCreate(
                        ['id_empleado' => $idEmpleado, 'nombre' => $campo],
                        ['valor' => $valor]
                    );
                }

                // 2) Guardar DOCUMENTOS
                foreach ($labelByKey as $inputKey => $label) {
                    $file = $request->file($inputKey) ?: $request->files->get($inputKey);
                    if (! $file instanceof \Illuminate\Http\UploadedFile  || ! $file->isValid()) {
                        throw new \RuntimeException("Archivo inválido/ausente: {$inputKey}");
                    }

                    $ext       = $file->getClientOriginalExtension();
                    $slugLabel = \Illuminate\Support\Str::slug($label, '_');
                    $baseName  = 'emp_' . $idEmpleado . '_' . $slugLabel;
                    $finalName = $baseName . '.' . $ext;
                    $i         = 1;
                    while (\File::exists($destBase . DIRECTORY_SEPARATOR . $finalName)) {
                        $finalName = $baseName . '_' . $i . '.' . $ext;
                        $i++;
                    }

                    $file->move($destBase, $finalName);

                    \App\Models\DocumentEmpleado::create([
                        'employee_id'     => $idEmpleado,
                        'name'            => str_replace('\\', '/', $relativeDir . '/' . $finalName), // ruta relativa en storagetalentsafe
                        'id_opcion'       => null,
                        'expiry_date'     => null,
                        'expiry_reminder' => null,
                        'nameDocument'    => $label, // nombre legible/personalizado
                        'status'          => 1,
                    ]);

                    \Log::info('✅ [NuevoIngreso] archivo guardado', [
                        'key'         => $inputKey,
                        'label'       => $label,
                        'stored_name' => $finalName,
                        'dest'        => $destBase,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            \Log::error('❌ [NuevoIngreso] error al guardar', ['msg' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Ocurrió un error al guardar. Intenta nuevamente.'])
                ->withInput();
        }

        // 🔒 Revoca el link ahora que todo se guardó bien
        $this->revokeCurrentLink((int) $idEmpleado);

        // (Opcional) limpia también id_empleado/id_portal para evitar re-envíos con back button
        session()->forget(['id_empleado', 'id_portal']);

      return response()->view('registro.graciasCandidato'); 

    }

    private function revokeCurrentLink(int $idEmpleado): bool
    {
        $jti   = session('token_jti');
        $sha16 = session('token_sha16');

        // 1) Intento directo por jti/sha16 (lo ideal)
        $q = LinkEmpleado::query()->where('id_empleado', $idEmpleado);
        if (! empty($jti)) {$q->where('jti', $jti);} elseif (! empty($sha16)) {$q->where('token_sha16', $sha16);}

        $updated = $q->update([
            'is_used'    => 1,
            'used_at'    => now(),
            'revoked_at' => now(),
        ]);

        // 2) Fallback: si no se actualizó nada (no jti/sha16 o no match),
        //    intenta con el último link activo de ese empleado.
        if ($updated === 0) {
            $last = LinkEmpleado::where('id_empleado', $idEmpleado)
                ->where(function ($qq) {
                    $qq->whereNull('revoked_at')
                        ->orWhere('is_used', 0);
                })
                ->orderByDesc('id')
                ->first();

            if ($last) {
                $last->update([
                    'is_used'    => 1,
                    'used_at'    => now(),
                    'revoked_at' => now(),
                ]);
                $updated = 1;
            }
        }

        Log::info('[NuevoIngreso] link revocado', [
            'id_empleado' => $idEmpleado,
            'jti'         => $jti,
            'sha16'       => $sha16,
            'updated'     => $updated,
        ]);

        // Limpia el rastro del token en sesión (opcional pero recomendado)
        session()->forget(['token_jti', 'token_sha16']);

        return $updated > 0;
    }

}
