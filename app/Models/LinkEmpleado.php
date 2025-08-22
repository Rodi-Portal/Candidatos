<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkEmpleado extends Model
{
    protected $table = 'links_empleados';   // ← ajusta si tu tabla se llama distinto
    public $timestamps = false;             // la tabla usa creacion/edicion, no created_at/updated_at

    protected $fillable = [
        'id_empleado','link','qr','eliminado',
        'jti','token_sha16','exp_unix',
        'is_used','used_at','revoked_at',
    ];
}
