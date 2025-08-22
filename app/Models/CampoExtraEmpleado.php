<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampoExtraEmpleado extends Model
{
    protected $table = 'empleado_campos_extra';   // ← tal cual la nombraste
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Timestamps no estándar
    public $timestamps = true;
    const CREATED_AT = 'creacion';
    const UPDATED_AT = 'edicion';

    protected $fillable = [
        'id_empleado', 'nombre', 'valor',
    ];
}
