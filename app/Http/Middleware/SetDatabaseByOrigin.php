<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SetDatabaseByOrigin
{
    public function handle($request, Closure $next)
    {
        $origin = $request->getHost(); 

        if (str_contains($origin, 'localhost') || str_contains($origin, '127.0.0.1')) {
            // 🟢 Desarrollo local
            Log::info('🌱 Usando configuración local');

            Config::set('database.connections.mysql.database', env('DB_DATABASE_TEST'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME_TEST'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD_TEST'));

        } elseif (str_contains($origin, 'sandbox.talentsafecontrol.com') || str_contains($origin, 'pruebas.')) {
            // 🔸 Entorno de pruebas
            Config::set('database.connections.mysql.database', env('DB_DATABASE_SANDBOX'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME_SANDBOX'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD_SANDBOX'));

        } elseif (str_contains($origin, 'portal.talentsafecontrol.com')){ 
            // 🔹 Producción
            Config::set('database.connections.mysql.database', env('DB_DATABASE_PROD'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME_PROD'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD_PROD'));
        }

        return $next($request);
    }
}

