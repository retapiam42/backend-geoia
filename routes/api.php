<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AdjuntoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// -- arreglar --

Route::view('denuncias', DenunciaController::class);
Route::view('documentos', DocumentoController::class);
Route::view('movimientos', MovimientoController::class);
Route::view('donaciones', DonacionController::class);
Route::view('proyectos', ProyectoController::class);
Route::view('adjuntos', AdjuntoController::class);

// -- Apoyo Contra la Corrupción --
// Routes for agregarcarrosController and EstadosController removed as controllers do not exist

// -- Consultoría Anticorrupción --
Route::view('/consultoria-anticorrupcion', 'consultoria-anticorrupcion')->name('consultoria.anticorrupcion');

// -- Geo IA --
Route::view('/geo-ia', 'geo-ia')->name('geo.ia');

// -- Marco Normativo Integral y Código de Conducta Veeduria --
Route::view('/marco-normativo', 'marco-normativo')->name('marco.normativo');

// -- Nosotros --
Route::view('/nosotros', 'nosotros')->name('nosotros');

// -- Observatorio CCVP --
Route::view('/observatorio-ccvp', 'observatorio-ccvp')->name('observatorio.ccvp');

// -- Registro --
Route::view('/registro', 'registro')->name('registro');

// Rutas para el registro de usuarios
Route::post("/login", [RegistroController::class, "Login"]);

// -- Usuario --
Route::apiResource('usuarios', UsuarioController::class);
Route::view('/usuario', 'usuario')->name('usuario');

Route::post('/registro', [UsuarioController::class, 'Registro']);
Route::get("/listarusuario", [UsuarioController::class,"index"]);
Route::put("/actualizarusuario/{usuario}", [UsuarioController::class,"update"]);
Route::delete("/eliminarusuario/{usuario}",[UsuarioController::class,"destroy"]);

// -- Veeduria IA --
Route::view('/veeduria-ia', 'veeduria-ia')->name('veeduria.ia'); 

// -- Blog Routes --
// Route::get('blog', 'BlogController@index')->name('blog.index');
// Route::get('blog/{post:slug}', 'BlogController@show')->name('blog.show');

// -- index probar --
// Route::get('/datos', [ApiController::class, 'index']);

// Ruta de prueba para verificar que la API funciona
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'mensaje' => 'API de Mecaza funcionando correctamente',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
