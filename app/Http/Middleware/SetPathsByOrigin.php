<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SetPathsByOrigin
{
    public function handle($request, Closure $next)
    {
        $host = $request->getHost();

        if (Str::contains($host, '127.0.0.1') || Str::contains($host, 'localhost')) {
            Log::info('🟢 Rutas locales para archivos');
            Config::set('paths.logo',    env('LOGOS_PATH_LOCAL'));
            Config::set('paths.privacy', env('PRIVACY_PATH_LOCAL'));

        } elseif (Str::contains($host, 'sandboxcandidatos.talentsafecontrol.com')) {
            Log::info('🔸 Rutas sandbox para archivos');
            Config::set('paths.logo',    env('LOGOS_PATH_SAND'));
            Config::set('paths.privacy', env('PRIVACY_PATH_SAND'));

        } elseif (Str::contains($host, 'bolsatrabajo.talentsafecontrol.com')) {
            Log::info('🔹 Rutas producción para archivos');
            Config::set('paths.logo',    env('LOGOS_PATH_PROD'));
            Config::set('paths.privacy', env('PRIVACY_PATH_PROD'));
        } else {
            // Fallback a producción
            Config::set('paths.logo',    env('LOGOS_PATH_PROD'));
            Config::set('paths.privacy', env('PRIVACY_PATH_PROD'));
        }

        return $next($request);
    }
}
