<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisicion extends Model
{
    use HasFactory;

    // Nombre de la tabla (si no sigue la convención)
    protected $table = 'requisicion';

    // PK
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Timestamps personalizados
    public $timestamps = true;
    const CREATED_AT = 'creacion';
    const UPDATED_AT = 'edicion';

    // Campos asignables en masa
    protected $fillable = [
        'creacion',
        'edicion',
        'id_portal',
        'id_usuario',
        'id_usuario_cliente',
        'id_cliente',
        'tipo',
        'puesto',
        'numero_vacantes',
        'escolaridad',
        'estatus_escolar',
        'otro_estatus_escolar',
        'carrera_requerida',
        'idiomas',
        'otros_estudios',
        'habilidad_informatica',
        'genero',
        'estado_civil',
        'edad_minima',
        'edad_maxima',
        'licencia',
        'discapacidad_aceptable',
        'causa_vacante',
        'lugar_residencia',
        'jornada_laboral',
        'tiempo_inicio',
        'tiempo_final',
        'dias_descanso',
        'disponibilidad_viajar',
        'disponibilidad_horario',
        'lugar_entrevista',
        'zona_trabajo',
        'sueldo',
        'sueldo_adicional',
        'tipo_pago_sueldo',
        'sueldo_minimo',
        'sueldo_maximo',
        'tipo_prestaciones',
        'tipo_prestaciones_superiores',
        'otras_prestaciones',
        'experiencia',
        'actividades',
        'competencias',
        'observaciones',
        'comentario_final',
        'status',
        'eliminado',

        // si ya agregaste la FK a la tabla intake:
        'id_intake',
    ];

    // Casts útiles
    protected $casts = [
        'creacion'               => 'datetime',
        'edicion'                => 'datetime',
        'id_portal'              => 'integer',
        'id_usuario'             => 'integer',
        'id_usuario_cliente'     => 'integer',
        'id_cliente'             => 'integer',
        'numero_vacantes'        => 'integer',
        'edad_minima'            => 'integer',
        'edad_maxima'            => 'integer',
        'status'                 => 'integer',
        'eliminado'              => 'integer',
        'id_intake'              => 'integer',
    ];

    /**
     * Relación (opcional) con el intake flexible.
     * Quita esto si aún no agregas la columna id_intake y la tabla requisicion_intake.
     */
    public function intake()
    {
        return $this->belongsTo(RequisicionIntake::class, 'id_intake');
    }
}
