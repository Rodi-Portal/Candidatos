<?php

// app/Models/RequisicionIntake.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisicionIntake extends Model
{
    protected $table = 'requisicion_intake';
    public $timestamps = false; // usamos creacion/edicion

    protected $fillable = [
        'id_portal','id_usuario_token','id_cliente',
        'nombre_cliente','email','telefono','metodo_comunicacion',
        'razon_social','nit','pais_empresa','pais_otro','actividad','sitio_web','fecha_solicitud',
        'plan','requiere_voip','voip_propiedad','voip_pais_ciudad',
        'miembro_bni','referido','horario','sexo_preferencia','rango_edad',
        'funciones','requisitos','recursos',
        'usa_crm','crm_nombre','fecha_inicio','observaciones',
        'archivo_path',
        'acepta_terminos','terminos_file','terminos_hash',
        'extras',
        'creacion','edicion',
    ];

    protected $casts = [
        'acepta_terminos' => 'boolean',
        'extras'          => 'array',
        'fecha_solicitud' => 'date',
        'fecha_inicio'    => 'date',
        'creacion'        => 'datetime',
        'edicion'         => 'datetime',
    ];

    // FK hacia la tabla clásica
    public function requisicion()
    {
        return $this->hasOne(\App\Models\Requisicion::class, 'id_intake');
    }
}
