<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DenunciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $denuncias = Denuncia::with(['usuario', 'adjuntos'])->get();
        
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
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'nullable|exists:usuarios,usuarios_id',
            'nombre_anonimo' => 'nullable|string|max:255',
            'email_anonimo' => 'nullable|email|max:255',
            'telefono_anonimo' => 'nullable|string|max:20',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'anonima' => 'required|boolean',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,avi,mov|max:10240', // 10MB max
            'ubicacion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
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
                    'message' => 'Para denuncias anónimas debe proporcionar al menos nombre o email',
                    'errors' => ['anonima' => 'Datos de contacto requeridos para denuncia anónima']
                ], 422);
            }
        } else {
            if (empty($request->usuario_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Para denuncias no anónimas debe proporcionar un usuario',
                    'errors' => ['usuario_id' => 'Usuario requerido para denuncia no anónima']
                ], 422);
            }
        }

        try {
            // Handle file upload if present
            $archivoPath = null;
            $tipoArchivo = null;
            $nombreArchivo = null;
            
            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $archivoPath = $file->storeAs('denuncias', $fileName, 'public');
                $tipoArchivo = $file->getClientMimeType();
                $nombreArchivo = $file->getClientOriginalName();
            }

            // Create a new denuncia
            $denuncia = Denuncia::create([
                'usuario_id' => $request->usuario_id,
                'nombre_anonimo' => $request->nombre_anonimo,
                'email_anonimo' => $request->email_anonimo,
                'telefono_anonimo' => $request->telefono_anonimo,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'anonima' => $request->anonima,
                'archivo_path' => $archivoPath,
                'tipo_archivo' => $tipoArchivo,
                'nombre_archivo' => $nombreArchivo,
                'estado' => 'pendiente',
                'ubicacion' => $request->ubicacion,
                'observaciones' => $request->observaciones,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Denuncia creada exitosamente',
                'data' => $denuncia->load(['usuario', 'adjuntos'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la denuncia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $denuncia = Denuncia::with(['usuario', 'adjuntos'])->find($id);

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
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'nullable|exists:usuarios,usuarios_id',
            'nombre_anonimo' => 'nullable|string|max:255',
            'email_anonimo' => 'nullable|email|max:255',
            'telefono_anonimo' => 'nullable|string|max:20',
            'titulo' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'anonima' => 'sometimes|boolean',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,avi,mov|max:10240',
            'estado' => 'sometimes|in:pendiente,en_revision,resuelta,rechazada',
            'ubicacion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the denuncia
        $denuncia = Denuncia::find($id);

        if (!$denuncia) {
            return response()->json([
                'success' => false,
                'message' => 'Denuncia no encontrada'
            ], 404);
        }

        try {
            // Handle file upload if present
            if ($request->hasFile('archivo')) {
                // Delete old file if exists
                if ($denuncia->archivo_path) {
                    Storage::disk('public')->delete($denuncia->archivo_path);
                }
                
                $file = $request->file('archivo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $archivoPath = $file->storeAs('denuncias', $fileName, 'public');
                $request->merge([
                    'archivo_path' => $archivoPath,
                    'tipo_archivo' => $file->getClientMimeType(),
                    'nombre_archivo' => $file->getClientOriginalName()
                ]);
            }

            // Update the denuncia
            $denuncia->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Denuncia actualizada exitosamente',
                'data' => $denuncia->load(['usuario', 'adjuntos'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la denuncia',
                'error' => $e->getMessage()
            ], 500);
        }
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

        try {
            // Delete file if exists
            if ($denuncia->archivo_path) {
                Storage::disk('public')->delete($denuncia->archivo_path);
            }

            $denuncia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Denuncia eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la denuncia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get denuncias by status
     */
    public function getByStatus($status)
    {
        $denuncias = Denuncia::with(['usuario', 'adjuntos'])
            ->where('estado', $status)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $denuncias
        ]);
    }

    /**
     * Get anonymous denuncias
     */
    public function getAnonymous()
    {
        $denuncias = Denuncia::with(['adjuntos'])
            ->where('anonima', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $denuncias
        ]);
    }

    /**
     * Get non-anonymous denuncias
     */
    public function getNonAnonymous()
    {
        $denuncias = Denuncia::with(['usuario', 'adjuntos'])
            ->where('anonima', false)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $denuncias
        ]);
    }
}
