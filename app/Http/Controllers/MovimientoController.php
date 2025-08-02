<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movimientos = Movimiento::with('donacion')->get();
        return response()->json([
            'success' => true,
            'data' => $movimientos
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
            'donacion_id' => 'required|integer|exists:donacions,donacions_id',
            'tipo' => 'required|string|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
        ]);

        $movimiento = Movimiento::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Movimiento creado exitosamente',
            'data' => $movimiento
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movimiento = Movimiento::with('donacion')->find($id);

        if (!$movimiento) {
            return response()->json([
                'success' => false,
                'message' => 'Movimiento no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $movimiento
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimiento $movimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'donacion_id' => 'sometimes|integer|exists:donacions,donacions_id',
            'tipo' => 'sometimes|string|in:ingreso,egreso',
            'monto' => 'sometimes|numeric|min:0',
            'descripcion' => 'sometimes|string',
        ]);

        $movimiento = Movimiento::find($id);

        if (!$movimiento) {
            return response()->json([
                'success' => false,
                'message' => 'Movimiento no encontrado'
            ], 404);
        }

        $movimiento->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Movimiento actualizado exitosamente',
            'data' => $movimiento
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $movimiento = Movimiento::find($id);

        if (!$movimiento) {
            return response()->json([
                'success' => false,
                'message' => 'Movimiento no encontrado'
            ], 404);
        }

        $movimiento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Movimiento eliminado exitosamente'
        ]);
    }
}
