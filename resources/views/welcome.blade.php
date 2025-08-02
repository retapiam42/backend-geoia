<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API de Veeduría</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .endpoint {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .method {
            font-weight: bold;
            color: #007bff;
        }
        .url {
            font-family: monospace;
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 API de Veeduría</h1>
        <p class="success">✅ API funcionando correctamente</p>
        
        <h2>📋 Endpoints Disponibles:</h2>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/test</span> - Verificar estado de la API
        </div>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/usuarios</span> - Listar usuarios
        </div>
        
        <div class="endpoint">
            <span class="method">POST</span> <span class="url">/api/registro</span> - Registrar usuario
        </div>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/denuncias</span> - Listar denuncias
        </div>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/proyectos</span> - Listar proyectos
        </div>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/donaciones</span> - Listar donaciones
        </div>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/movimientos</span> - Listar movimientos
        </div>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/documentos</span> - Listar documentos
        </div>
        
        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/api/adjuntos</span> - Listar adjuntos
        </div>
        
        <h2>🔧 Prueba la API:</h2>
        <p>Para probar la API, visita: <a href="/api/test" target="_blank">/api/test</a></p>
        
        <h2>📚 Documentación:</h2>
        <p>Esta es una API RESTful para el sistema de veeduría. Todos los endpoints devuelven respuestas en formato JSON.</p>
        
        <p><strong>Versión:</strong> 1.0.0</p>
        <p><strong>Timestamp:</strong> {{ now() }}</p>
    </div>
</body>
</html> 