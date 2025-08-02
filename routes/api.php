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

// Ruta de prueba para verificar que la API funciona
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'mensaje' => 'API de Veeduría funcionando correctamente',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Usuario API routes
Route::apiResource('usuarios', UsuarioController::class);
Route::post('/registro', [UsuarioController::class, 'Registro']);
Route::get('/usuario', [UsuarioController::class, 'usuario'])->name('usuario');

// Denuncias API routes - Rutas específicas primero
Route::get('/denuncias/estado/{status}', [DenunciaController::class, 'getByStatus']);
Route::get('/denuncias/anónimas', [DenunciaController::class, 'getAnonymous']);
Route::get('/denuncias/no-anónimas', [DenunciaController::class, 'getNonAnonymous']);
Route::apiResource('denuncias', DenunciaController::class);

// Proyectos API routes
Route::apiResource('proyectos', ProyectoController::class);

// Donaciones API routes - Rutas específicas primero
Route::post('/donaciones/{id}/confirmar-pago', [DonacionController::class, 'confirmarPago']);
Route::get('/donaciones/estado-pago/{status}', [DonacionController::class, 'getByPaymentStatus']);
Route::get('/donaciones/anónimas', [DonacionController::class, 'getAnonymous']);
Route::get('/donaciones/no-anónimas', [DonacionController::class, 'getNonAnonymous']);
Route::get('/donaciones/proyecto/{projectId}', [DonacionController::class, 'getByProject']);
Route::apiResource('donaciones', DonacionController::class);

// Movimientos API routes
Route::apiResource('movimientos', MovimientoController::class);

// Documentos API routes
Route::apiResource('documentos', DocumentoController::class);

// Adjuntos API routes
Route::apiResource('adjuntos', AdjuntoController::class);

// Ruta de autenticación (si se necesita)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
