<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;

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
