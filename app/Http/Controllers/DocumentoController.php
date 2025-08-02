<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentos = Documento::all();
        return response()->json([
            'success' => true,
            'data' => $documentos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ruta_archivo' => 'required|string'
        ]);

        // Create a new documento
        $documento = Documento::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Documento creado exitosamente',
            'data' => $documento
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $documento = Documento::find($id);

        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $documento
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'ruta_archivo' => 'sometimes|string'
        ]);

        // Find the documento
        $documento = Documento::find($id);

        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento no encontrado'
            ], 404);
        }

        // Update the documento
        $documento->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Documento actualizado exitosamente',
            'data' => $documento
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $documento = Documento::find($id);

        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento no encontrado'
            ], 404);
        }

        $documento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Documento eliminado exitosamente'
        ]);
    }
}
