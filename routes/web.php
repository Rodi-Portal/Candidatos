<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RequisicionController;
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
    if (!preg_match('/^[\w\-]+\.(png|jpg|jpeg|webp)$/i', $filename)) {
        abort(403, 'Archivo no permitido');
    }

    $base = config('paths.logo');
    $path = $base . $filename;

    if (!file_exists($path)) {
        abort(404, 'Logo no encontrado');
    }

    return response()->file($path);
});

Route::get('/aviso/{filename}', function ($filename) {
    if (!preg_match('/^[\w\-\s]+\.pdf$/i', $filename)) {
        abort(403, 'Archivo no permitido');
    }

    $base = config('paths.privacy');
    $path = $base . $filename;

    if (!file_exists($path)) {
        abort(404, 'Aviso no encontrado');
    }

    return Response::file($path, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
});

Route::get('/terminos/{filename}', function ($filename) {
    if (!preg_match('/^[\w\-\s]+\.pdf$/i', $filename)) {
        abort(403, 'Archivo no permitido');
    }

    $base = config('paths.privacy'); 
    $path = $base . $filename;

    if (!file_exists($path)) {
        dd("No se encontró el archivo: $path");
        abort(404, 'Términos no encontrados');
    }

    return Response::file($path, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$filename.'"'
    ]);
});

// Abre el formulario de edición con ?token=<JWT RS256>
Route::get('/intake/editar', [RequisicionController::class, 'editIntake'])
    ->name('intake.edit');

// Actualiza (AJAX). Usa PATCH; si prefieres POST con method spoofing, cámbialo.
Route::patch('/intake/{rid}', [RequisicionController::class, 'updateIntake'])
    ->name('intake.update');
