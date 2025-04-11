<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BolsaTrabajoHistorialEmpleos extends Model
{
    use HasFactory;
    public $timestamps = false;
    // Especificamos el nombre de la tabla
    protected $table = 'bolsa_trabajo_historial_empleos';

    // Especificamos los campos que pueden ser asignados masivamente
    protected $fillable = [
        'creacion',
        'id_bolsa_trabajo',
        'empresa',
        'periodo',
        'puesto',
        'sueldo',
        'causa_separacion',
        'telefono',
    ];

    // Los atributos que deberían ser casteados a tipos específicos
    protected $casts = [
        'creacion' => 'datetime',
    ];

    public function bolsaTrabajo()
    {
        return $this->belongsTo(BolsaTrabajo::class, 'id_bolsa_trabajo', 'id');
    }
}
