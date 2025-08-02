<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api', function () {
    return response()->json([
        'success' => true,
        'message' => 'API de Veeduría funcionando correctamente',
        'endpoints' => [
            'usuarios' => '/api/usuarios',
            'denuncias' => '/api/denuncias',
            'proyectos' => '/api/proyectos',
            'donaciones' => '/api/donaciones',
            'movimientos' => '/api/movimientos',
            'documentos' => '/api/documentos',
            'adjuntos' => '/api/adjuntos',
            'test' => '/api/test'
        ],
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});
