<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentEmpleado extends Model
{
    protected $table = 'documents_empleado';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Timestamps personalizados
    public $timestamps = true;
    const CREATED_AT = 'creacion';
    const UPDATED_AT = 'edicion';

    protected $fillable = [
        'employee_id',
        'name',              // etiqueta del tipo de documento (ej. "Cédula de identidad")
        'id_opcion',         // si usas catálogo, si no déjalo null
        'description',
        'expiry_date',
        'expiry_reminder',
        'nameDocument',      // nombre o ruta relativa del archivo guardado
        'status',
    ];
}
