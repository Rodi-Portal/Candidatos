<?php
namespace App\Http\Controllers;

use App\Models\BolsaTrabajo;
use App\Models\BolsaTrabajoHistorialEmpleos;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistroController extends Controller
{
    public function mostrarFormulario(Request $request)
    {
        // Recupera el token desde la URL
        $token = $request->query('token');
        Log::info('Token recibido:', ['token' => $token]);

        // Verificar si el token no está presente
        if (! $token) {
            abort(403, 'Token no proporcionado.');
        }

        try {
            // Recupera las claves desde la configuración o el archivo .env
            $publicKeyPath = config('jwt.public_key');
            if (! file_exists($publicKeyPath)) {
                Log::error('La clave pública no existe en: ' . $publicKeyPath);
                abort(500, 'Clave pública no encontrada.');
            }

            $publicKey = file_get_contents($publicKeyPath);
            if (! $publicKey) {
                Log::error('No se pudo leer la clave pública.');
                abort(500, 'Error al leer clave pública.');
            }
            // Verificación y decodificación del token
            // Si usas RS256, debes pasar la clave pública para verificar el token
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));

            // Extraer los datos del token
            $cliente    = $decoded->NombrePortal ?? null;
            $id_usuario = $decoded->idUsuario ?? null;
            $id_portal  = $decoded->idPortal ?? null;
            $logo    = $decoded->logo ?? 'portal_icon.png';
            $aviso    = $decoded->aviso ?? 'AV_TL_V1.pdf';

             // Si el logo no está en el token, se usa 'portal_icon.png'
             // Si el logo no está en el token, se usa 'portal_icon.png'

            // Datos adicionales (ejemplo de listas predefinidas)
            $civiles = collect([
                (object) ['nombre' => 'Solter(@)'],
                (object) ['nombre' => 'Casad(@)'],
                (object) ['nombre' => 'Divorciad(@)'],
                (object) ['nombre' => 'Viud(@)'],
            ]);

            $grados = collect([
                (object) ['nombre' => 'Primaria'],
                (object) ['nombre' => 'Secundaria'],
                (object) ['nombre' => 'Preparatoria'],
                (object) ['nombre' => 'Carrera Tecnica'],
                (object) ['nombre' => 'Universidad'],
                (object) ['nombre' => 'Maestria'],
                (object) ['nombre' => 'Doctorado'],
            ]);

            // Retornar la vista con los datos decodificados
            return view('registro', compact('cliente', 'id_usuario', 'id_portal', 'civiles', 'grados', 'logo', 'aviso'));

        } catch (\Exception $e) {
            // Si ocurre un error con el JWT (token inválido o expirado)
            Log::error('Error al decodificar el token JWT', ['exception' => $e->getMessage()]);

            abort(401, 'Token inválido o expirado.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Campos para bolsa_trabajo
            'id_portal'                  => 'required|numeric',
            'id_usuario'                 => 'required|numeric',
            'nombre'                     => 'required|regex:/^[A-ZÁÉÍÓÚÑ ]+$/i',
            'paterno'                    => 'required|regex:/^[A-ZÁÉÍÓÚÑ ]+$/i',
            'materno'                    => 'nullable|regex:/^[A-ZÁÉÍÓÚÑ ]+$/i',
            'domicilio'                  => 'required|string',
            'fecha_nacimiento'           => 'required|date|before:today',
            'telefono'                   => 'required|regex:/^[0-9+\-\s]+$/',
            'nacionalidad'               => 'required|string',
            'civil'                      => 'required|string',
            'dependientes'               => 'required|string',
            'grado_estudios'             => 'required|string',
            'salud'                      => 'required|string',
            'enfermedad'                 => 'required|string',
            'deporte'                    => 'required|string',
            'metas'                      => 'required|string',
            'idiomas'                    => 'required|string',
            'maquinas'                   => 'required|string',
            'software'                   => 'required|string',
            'medio_contacto'             => 'required|string',
            'area_interes'               => 'required|string',
            'sueldo_deseado'             => 'required|numeric|min:0',
            'otros_ingresos'             => 'required|string',
            'viajar'                     => 'required|string',
            'trabajar'                   => 'required|string',
            'aviso'                      => 'accepted',

            // Campos para empleos
            'empleos'                    => 'array',
            'empleos.*.empresa'          => 'required|string',
            'empleos.*.periodo'          => 'required|string',
            'empleos.*.puesto'           => 'required|string',
            'empleos.*.sueldo'           => 'required|numeric|min:0',
            'empleos.*.causa_separacion' => 'required|string',
            'empleos.*.telefono'         => 'required|regex:/^[0-9+\-\s]+$/',
        ]);

        $existe = BolsaTrabajo::where('id_portal',        $validated['id_portal'])
        ->where('nombre',       $validated['nombre'])
        ->where('paterno',      $validated['paterno'])
        ->where('telefono', $validated['telefono'])
        ->where('fecha_nacimiento', $validated['fecha_nacimiento'])
        ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ya existe un aspirante con el mismo nombre, apellido y fecha de nacimiento en la  bolsa.'
            ], 422);
        }

        // ✅ Guardar en la tabla bolsa_trabajo
        try {
            DB::beginTransaction();
    
            $bolsa = new BolsaTrabajo();
            $bolsa->fill([
                'creacion'         => now(),
                'edicion'          => now(),
                'id_portal'        => $validated['id_portal'],
                'id_usuario'       => $validated['id_usuario'],
                'nombre'           => $validated['nombre'],
                'paterno'          => $validated['paterno'],
                'materno'          => $validated['materno'],
                'domicilio'        => $validated['domicilio'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'edad'             => \Carbon\Carbon::parse($validated['fecha_nacimiento'])->age,
                'telefono'         => $validated['telefono'],
                'nacionalidad'     => $validated['nacionalidad'],
                'civil'            => $validated['civil'],
                'dependientes'     => $validated['dependientes'],
                'grado_estudios'   => $validated['grado_estudios'],
                'salud'            => $validated['salud'],
                'enfermedad'       => $validated['enfermedad'],
                'deporte'          => $validated['deporte'],
                'metas'            => $validated['metas'],
                'idiomas'          => $validated['idiomas'],
                'maquinas'         => $validated['maquinas'],
                'software'         => $validated['software'],
                'medio_contacto'   => $validated['medio_contacto'],
                'area_interes'     => $validated['area_interes'],
                'sueldo_deseado'   => $validated['sueldo_deseado'],
                'otros_ingresos'   => $validated['otros_ingresos'],
                'viajar'           => $validated['viajar'],
                'trabajar'         => $validated['trabajar'],
                'comentario'       => '',
                'status'           => 1,
            ]);
    
            if (! $bolsa->save()) {
                throw new \Exception('No se pudo guardar el registro principal');
            }
    
            // Guardar empleos
            foreach ($validated['empleos'] as $empleo) {
                $hist = BolsaTrabajoHistorialEmpleos::create([
                   'creacion'         => now(),
                'id_bolsa_trabajo' => $bolsa->id,
                'empresa'          => $empleo['empresa'],
                'periodo'          => $empleo['periodo'],
                'puesto'           => $empleo['puesto'],
                'sueldo'           => $empleo['sueldo'],
                'causa_separacion' => $empleo['causa_separacion'],
                'telefono'         => $empleo['telefono'],
            
                ]);
    
                if (! $hist) {
                    throw new \Exception("No se pudo guardar empleo #{$bolsa->id}");
                }
            }
    
            DB::commit();
    
            // Devuelves también el ID si te interesa
            return response()->json([
                'message' => 'Registro exitoso. Gracias por tu interés. Por favor, mantente atento a los medios de contacto que nos proporcionaste, pues a través de ellos te contactaremos si surge alguna vacante acorde a tu perfil.',
                'bolsa_id'  => $bolsa->id,
            ], 201);
    
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error de base de datos',
                'error'   => $e->getMessage(),
            ], 500);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error inesperado',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
