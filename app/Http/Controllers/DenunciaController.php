<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use Illuminate\Http\Request;

class DenunciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $denuncias = Denuncia::all();
        return response()->json([
            'success' => true,
            'data' => $denuncias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'usuario_id' => 'required|integer|exists:usuarios,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'anonima' => 'required|boolean'
        ]);

        // Create a new denuncia
        $denuncia = Denuncia::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Denuncia creada exitosamente',
            'data' => $denuncia
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $denuncia = Denuncia::find($id);

        if (!$denuncia) {
            return response()->json([
                'success' => false,
                'message' => 'Denuncia no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $denuncia
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'usuario_id' => 'sometimes|integer|exists:usuarios,id',
            'titulo' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'anonima' => 'sometimes|boolean'
        ]);

        // Find the denuncia
        $denuncia = Denuncia::find($id);

        if (!$denuncia) {
            return response()->json([
                'success' => false,
                'message' => 'Denuncia no encontrada'
            ], 404);
        }

        // Update the denuncia
        $denuncia->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Denuncia actualizada exitosamente',
            'data' => $denuncia
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $denuncia = Denuncia::find($id);

        if (!$denuncia) {
            return response()->json([
                'success' => false,
                'message' => 'Denuncia no encontrada'
            ], 404);
        }

        $denuncia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Denuncia eliminada exitosamente'
        ]);
    }
}
