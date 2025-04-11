<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bolsatrabajo;
use App\Models\BolsaTrabajoHistorialEmpleos;



class RegistroController extends Controller
{
    public function mostrarFormulario($cliente, $id_usuario, $id_portal)
    {
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
        $archivo = 'portal_icon.png';

        return view('registro', compact('cliente', 'id_usuario', 'id_portal', 'civiles', 'grados', 'archivo'));
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

        // ✅ Guardar en la tabla bolsa_trabajo
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
        $bolsa->save();

        // ✅ Guardar empleos en bolsa_trabajo_historial_empleos
        foreach ($validated['empleos'] as $empleo) {
            BolsaTrabajoHistorialEmpleos::create([
                'creacion'         => now(),
                'id_bolsa_trabajo' => $bolsa->id,
                'empresa'          => $empleo['empresa'],
                'periodo'          => $empleo['periodo'],
                'puesto'           => $empleo['puesto'],
                'sueldo'           => $empleo['sueldo'],
                'causa_separacion' => $empleo['causa_separacion'],
                'telefono'         => $empleo['telefono'],
            ]);
        }

        return response()->json(['message' => 'Registro exitoso'], 201);
    }

}
