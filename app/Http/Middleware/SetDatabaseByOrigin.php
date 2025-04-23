<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SetDatabaseByOrigin
{
    public function handle($request, Closure $next)
    {
        $origin = $request->getHost();

        // Nos aseguramos de que la conexión por defecto sea “mysql”
        Config::set('database.default', 'mysql');

        if (Str::contains($origin, ['127.0.0.1', 'localhost'])) {
            Log::info('🌱 DB Local');
            Config::set('database.connections.mysql.database', env('DB_DATABASE_TEST'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME_TEST'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD_TEST'));

        } elseif (Str::contains($origin, 'sandboxcandidatos.talentsafecontrol.com')) {
            Log::info('🔸 DB Sandbox');
            // No cambias host, asume localhost
            Config::set('database.connections.mysql.database', env('DB_DATABASE_SANDBOX'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME_SANDBOX'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD_SANDBOX'));

        } elseif (Str::contains($origin, 'bolsatrabajo.talentsafecontrol.com')) {
            Log::info('🔹 DB Producción');
            // Estos valores ahora salen de DB_DATABASE*, etc.
            Config::set('database.connections.mysql.database', env('DB_DATABASE_PROD'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME_PROD'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD_PROD'));
        } else {
            Log::warning("Host desconocido: {$origin}, usando DB por defecto.");
            // deja las vars definidas en .env DB_DATABASE / DB_USERNAME / DB_PASSWORD
        }

        return $next($request);
    }
}

