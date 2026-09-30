<?php
use App\Http\Controllers\RegistroAceptadoController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RequisicionController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/registro', [RegistroController::class, 'mostrarFormulario']);
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

/*Registro  formularios   Asistente Virtual  */
Route::get('/registro-nuevo', [RegistroController::class, 'mostrarFormularioNuevo']);
Route::post('/registro-nuevo', [RegistroController::class, 'storeNuevo'])->name('registro.storeNuevo');
// routes/web.php
Route::view('/registro/gracias', 'registro.gracias')->name('registro.gracias');
Route::view('/registro/graciasReq', 'registro.graciasReq')->name('registro.graciasReq');


Route::get('/solicitudes/crear', [RequisicionController::class, 'create'])->name('solicitudes.create');
Route::post('/solicitudes', [RequisicionController::class, 'store'])->name('solicitudes.store');

Route::get('/logo/{filename}', function ($filename) {
    if (! preg_match('/^[\w\-]+\.(png|jpg|jpeg|webp)$/i', $filename)) {
        abort(403, 'Archivo no permitido');
    }

    $idPortal = (int) session('id_portal');

    if ($idPortal <= 0) {
        abort(403, 'Portal no identificado');
    }

    $root = rtrim(
        str_replace('\\', '/', (string) config('paths.storage_root')),
        '/'
    );

    /*
     * El nombre recibido en la URL puede provenir de un token anterior.
     * La fuente actual del logo es portal.logo.
     */
    $portal = \Illuminate\Support\Facades\DB::table('portal')
        ->select('logo')
        ->where('id', $idPortal)
        ->first();

    $logo = trim((string) ($portal->logo ?? ''));

    if (
        $logo !== ''
        && preg_match('/^[\w\-]+\.(png|jpg|jpeg|webp)$/i', $logo)
    ) {
        $pathPortal = $root
            . '/portales/'
            . $idPortal
            . '/configuracion/logo/'
            . basename($logo);

        if (is_file($pathPortal)) {
            return response()->file($pathPortal, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);
        }
    }

    /*
     * Si el portal no tiene logo configurado o el archivo no existe,
     * usar el logo predeterminado.
     */
    $pathDefault = $root . '/default/logo/logo_nuevo.png';

    if (! is_file($pathDefault)) {
        abort(404, 'Logo no encontrado');
    }

    return response()->file($pathDefault, [
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma'        => 'no-cache',
        'Expires'       => '0',
    ]);
});


Route::get('/aviso/{filename}', function ($filename) {
    if (! preg_match('/^[\w\-\s]+\.pdf$/i', $filename)) {
        abort(403, 'Archivo no permitido');
    }

    $root = rtrim(
        str_replace('\\', '/', (string) config('paths.storage_root')),
        '/'
    );

    $path = $root
        . '/default/documentos/'
        . basename($filename);

    if (! is_file($path)) {
        abort(404, 'Aviso no encontrado');
    }

    return Response::file($path, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
    ]);
});


Route::get('/terminos/{filename}', function ($filename) {
    if (! preg_match('/^[\w\-\s]+\.pdf$/i', $filename)) {
        abort(403, 'Archivo no permitido');
    }

    $root = rtrim(
        str_replace('\\', '/', (string) config('paths.storage_root')),
        '/'
    );

    $path = $root
        . '/default/documentos/'
        . basename($filename);

    if (! is_file($path)) {
        abort(404, 'Términos no encontrados');
    }

    return Response::file($path, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
    ]);
});

// Abre el formulario de edición con ?token=<JWT RS256>
Route::get('/intake/editar', [RequisicionController::class, 'editIntake'])
    ->name('intake.edit');

// Actualiza (AJAX). Usa PATCH; si prefieres POST con method spoofing, cámbialo.
Route::patch('/intake/{rid}', [RequisicionController::class, 'updateIntake'])
    ->name('intake.update');

Route::get('/nuevo-ingreso', [RegistroAceptadoController::class, 'create'])
    ->name('nuevoIngreso.create');
Route::post('/nuevo-ingreso', [RegistroAceptadoController::class, 'store'])
    ->name('nuevo_ingreso.store');    // <-- ESTE name debe existir
