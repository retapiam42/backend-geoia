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
            'nombre_anonimo' => 'nullable|string|max:255',
            'email_anonimo' => 'nullable|email|max:255',
            'telefono_anonimo' => 'nullable|string|max:20',
            'proyecto_id' => 'nullable|exists:proyectos,proyectos_id',
            'monto' => 'required|numeric|min:0.01',
            'anonima' => 'required|boolean',
            'metodo_pago' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'documento' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB max
            'referencia_pago' => 'nullable|string|max:255',
            'numero_transaccion' => 'nullable|string|max:255',
            'estado_pago' => 'nullable|in:pendiente,confirmado,rechazado,cancelado',
            'comentarios' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate anonymous vs user submission
        if ($request->anonima) {
            if (empty($request->nombre_anonimo) && empty($request->email_anonimo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Para donaciones anónimas debe proporcionar al menos nombre o email',
                    'errors' => ['anonima' => 'Datos de contacto requeridos para donación anónima']
                ], 422);
            }
        } else {
            if (empty($request->usuario_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Para donaciones no anónimas debe proporcionar un usuario',
                    'errors' => ['usuario_id' => 'Usuario requerido para donación no anónima']
                ], 422);
            }
        }

        // Validate payment reference or file
        if (empty($request->referencia_pago) && empty($request->numero_transaccion) && !$request->hasFile('documento')) {
            return response()->json([
                'success' => false,
                'message' => 'Debe proporcionar al menos una referencia de pago o un archivo de evidencia',
                'errors' => ['referencia_pago' => 'Referencia de pago o archivo requerido']
            ], 422);
        }

        try {
            // Handle file upload if present
            $documentoPath = null;
            $tipoArchivo = null;
            $nombreArchivo = null;
            
            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $documentoPath = $file->storeAs('donaciones', $fileName, 'public');
                $tipoArchivo = $file->getClientMimeType();
                $nombreArchivo = $file->getClientOriginalName();
            }

            // Create a new donation
            $donacion = Donacion::create([
                'usuario_id' => $request->usuario_id,
                'nombre_anonimo' => $request->nombre_anonimo,
                'email_anonimo' => $request->email_anonimo,
                'telefono_anonimo' => $request->telefono_anonimo,
                'proyecto_id' => $request->proyecto_id,
                'monto' => $request->monto,
                'anonima' => $request->anonima,
                'metodo_pago' => $request->metodo_pago,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'documento_path' => $documentoPath,
                'tipo_archivo' => $tipoArchivo,
                'nombre_archivo' => $nombreArchivo,
                'referencia_pago' => $request->referencia_pago,
                'numero_transaccion' => $request->numero_transaccion,
                'estado_pago' => $request->estado_pago ?? 'pendiente',
                'estado' => 'activa',
                'comentarios' => $request->comentarios,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Donación registrada exitosamente',
                'data' => $donacion->load(['usuario', 'proyecto'])
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
            'nombre_anonimo' => 'nullable|string|max:255',
            'email_anonimo' => 'nullable|email|max:255',
            'telefono_anonimo' => 'nullable|string|max:20',
            'proyecto_id' => 'nullable|exists:proyectos,proyectos_id',
            'monto' => 'sometimes|numeric|min:0.01',
            'anonima' => 'sometimes|boolean',
            'metodo_pago' => 'sometimes|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'documento' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'referencia_pago' => 'nullable|string|max:255',
            'numero_transaccion' => 'nullable|string|max:255',
            'estado_pago' => 'sometimes|in:pendiente,confirmado,rechazado,cancelado',
            'estado' => 'sometimes|in:activa,completada,cancelada',
            'fecha_pago' => 'nullable|date',
            'comentarios' => 'nullable|string',
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
                $request->merge([
                    'documento_path' => $documentoPath,
                    'tipo_archivo' => $file->getClientMimeType(),
                    'nombre_archivo' => $file->getClientOriginalName()
                ]);
            }

            // Update the donation
            $donacion->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Donación actualizada exitosamente',
                'data' => $donacion->load(['usuario', 'proyecto'])
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

    /**
     * Confirm payment
     */
    public function confirmarPago($id)
    {
        $donacion = Donacion::find($id);

        if (!$donacion) {
            return response()->json([
                'success' => false,
                'message' => 'Donación no encontrada'
            ], 404);
        }

        $donacion->update([
            'estado_pago' => 'confirmado',
            'fecha_pago' => now(),
            'estado' => 'completada'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago confirmado exitosamente',
            'data' => $donacion->load(['usuario', 'proyecto'])
        ]);
    }

    /**
     * Get donations by payment status
     */
    public function getByPaymentStatus($status)
    {
        $donaciones = Donacion::with(['usuario', 'proyecto'])
            ->where('estado_pago', $status)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $donaciones
        ]);
    }

    /**
     * Get anonymous donations
     */
    public function getAnonymous()
    {
        $donaciones = Donacion::with(['proyecto'])
            ->where('anonima', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $donaciones
        ]);
    }

    /**
     * Get non-anonymous donations
     */
    public function getNonAnonymous()
    {
        $donaciones = Donacion::with(['usuario', 'proyecto'])
            ->where('anonima', false)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $donaciones
        ]);
    }

    /**
     * Get donations by project
     */
    public function getByProject($projectId)
    {
        $donaciones = Donacion::with(['usuario', 'proyecto'])
            ->where('proyecto_id', $projectId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $donaciones
        ]);
    }
}
