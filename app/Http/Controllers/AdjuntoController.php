<?php

namespace App\Http\Controllers;

use App\Models\Adjunto;
use Illuminate\Http\Request;

class AdjuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adjuntos = Adjunto::with('denuncia')->get();
        return response()->json([
            'success' => true,
            'data' => $adjuntos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'denuncia_id' => 'required|integer|exists:denuncias,denuncias_id',
            'archivo' => 'required|string',
            'tipo' => 'required|string|in:imagen,pdf,documento,otro',
        ]);

        $adjunto = Adjunto::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Adjunto creado exitosamente',
            'data' => $adjunto
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $adjunto = Adjunto::with('denuncia')->find($id);

        if (!$adjunto) {
            return response()->json([
                'success' => false,
                'message' => 'Adjunto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $adjunto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adjunto $adjunto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'denuncia_id' => 'sometimes|integer|exists:denuncias,denuncias_id',
            'archivo' => 'sometimes|string',
            'tipo' => 'sometimes|string|in:imagen,pdf,documento,otro',
        ]);

        $adjunto = Adjunto::find($id);

        if (!$adjunto) {
            return response()->json([
                'success' => false,
                'message' => 'Adjunto no encontrado'
            ], 404);
        }

        $adjunto->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Adjunto actualizado exitosamente',
            'data' => $adjunto
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $adjunto = Adjunto::find($id);

        if (!$adjunto) {
            return response()->json([
                'success' => false,
                'message' => 'Adjunto no encontrado'
            ], 404);
        }

        $adjunto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Adjunto eliminado exitosamente'
        ]);
    }
}
