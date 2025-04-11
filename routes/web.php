<?php

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
Route::get('/registro/{cliente}/{id_usuario}/{id_portal}', [RegistroController::class, 'mostrarFormulario']);
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');
Route::get('/logo/{filename}', function ($filename) {
    // Ruta para el entorno local
    $path = app()->environment('local') 
    ? env('LOGOS_PATH_LOCAL') . $filename
    : env('LOGOS_PATH_PROD') . $filename;

    // Validación para evitar path traversal
    if (!preg_match('/^[\w\-]+\.(png|jpg|jpeg|webp)$/', $filename)) {
        abort(403, 'Archivo no permitido');
    }

    // Verifica si el archivo existe
    if (!file_exists($path)) {
        abort(404, 'Logo no encontrado');
    }

    // Devuelve el archivo
    return response()->file($path);
});
Route::get('/', function () {
    return view('welcome');
});
