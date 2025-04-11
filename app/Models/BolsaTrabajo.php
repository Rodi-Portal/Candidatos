<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BolsaTrabajo extends Model
{
    use HasFactory;

    // Especificamos el nombre de la tabla, ya que no es plural
    protected $table = 'bolsa_trabajo';
    public $timestamps = false;
    // Especificamos los campos que pueden ser asignados masivamente
    protected $fillable = [
        'creacion',
        'edicion',
        'id_portal',
        'id_usuario',
        'nombre',
        'paterno',
        'materno',
        'domicilio',
        'fecha_nacimiento',
        'edad',
        'telefono',
        'nacionalidad',
        'civil',
        'dependientes',
        'grado_estudios',
        'salud',
        'enfermedad',
        'deporte',
        'metas',
        'idiomas',
        'maquinas',
        'software',
        'medio_contacto',
        'area_interes',
        'sueldo_deseado',
        'otros_ingresos',
        'viajar',
        'trabajar',
        'comentario',
        'status',
       
    ];

    // Los atributos que deberían ser casteados a tipos específicos
    protected $casts = [
        'creacion' => 'datetime',
        'edicion' => 'datetime',
    ];

    // Establecer las relaciones si es necesario (por ejemplo, con un modelo de Usuario)
    // public function usuario()
    // {
    //     return $this->belongsTo(User::class, 'id_usuario');
    // }
}
