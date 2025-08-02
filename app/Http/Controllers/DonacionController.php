<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DonacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donaciones = Donacion::with(['usuario', 'proyecto'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $donaciones
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'nullable|exists:usuarios,usuarios_id',
            'proyecto_id' => 'nullable|exists:proyectos,proyectos_id',
            'monto' => 'required|numeric|min:0.01',
            'anonima' => 'required|boolean',
            'metodo_pago' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
            'documento' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle file upload if present
            $documentoPath = null;
            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $documentoPath = $file->storeAs('donaciones', $fileName, 'public');
            }

            // Create a new donation
            $donacion = Donacion::create([
                'usuario_id' => $request->usuario_id,
                'proyecto_id' => $request->proyecto_id,
                'monto' => $request->monto,
                'anonima' => $request->anonima,
                'metodo_pago' => $request->metodo_pago,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'documento_path' => $documentoPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Donación registrada exitosamente',
                'data' => $donacion
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la donación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $donacion = Donacion::with(['usuario', 'proyecto'])->find($id);

        if (!$donacion) {
            return response()->json([
                'success' => false,
                'message' => 'Donación no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $donacion
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'nullable|exists:usuarios,usuarios_id',
            'proyecto_id' => 'nullable|exists:proyectos,proyectos_id',
            'monto' => 'sometimes|numeric|min:0.01',
            'anonima' => 'sometimes|boolean',
            'metodo_pago' => 'sometimes|string|max:255',
            'telefono' => 'sometimes|string|max:20',
            'correo' => 'sometimes|email|max:255',
            'documento' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the donation
        $donacion = Donacion::find($id);

        if (!$donacion) {
            return response()->json([
                'success' => false,
                'message' => 'Donación no encontrada'
            ], 404);
        }

        try {
            // Handle file upload if present
            if ($request->hasFile('documento')) {
                // Delete old file if exists
                if ($donacion->documento_path) {
                    Storage::disk('public')->delete($donacion->documento_path);
                }
                
                $file = $request->file('documento');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $documentoPath = $file->storeAs('donaciones', $fileName, 'public');
                $request->merge(['documento_path' => $documentoPath]);
            }

            // Update the donation
            $donacion->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Donación actualizada exitosamente',
                'data' => $donacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la donación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $donacion = Donacion::find($id);

        if (!$donacion) {
            return response()->json([
                'success' => false,
                'message' => 'Donación no encontrada'
            ], 404);
        }

        try {
            // Delete document file if exists
            if ($donacion->documento_path) {
                Storage::disk('public')->delete($donacion->documento_path);
            }

            $donacion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Donación eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la donación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
