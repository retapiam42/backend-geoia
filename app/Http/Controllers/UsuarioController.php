<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json([
            'success' => true,
            'data' => $usuarios
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint para crear usuario - Use POST /api/usuarios',
            'example' => [
                'nombre' => 'Usuario Ejemplo',
                'email' => 'usuario@example.com',
                'tel' => '123456789',
                'password' => '123456',
                'anonimo' => false
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'tel' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'anonimo' => 'required|boolean',
        ]);

        $usuario = Usuario::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'tel' => $validated['tel'] ?? null,
            'password' => bcrypt($validated['password']),
            'anonimo' => $validated['anonimo'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $usuario
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return response()->json([
            'success' => true,
            'data' => $usuario
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint para editar usuario - Use PUT /api/usuarios/' . $usuario->usuarios_id,
            'data' => $usuario
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:usuarios,email,' . $usuario->usuarios_id . ',usuarios_id',
            'tel' => 'nullable|string|max:20',
            'password' => 'sometimes|required|string|min:6',
            'anonimo' => 'sometimes|required|boolean',
        ]);

        if (isset($validated['nombre'])) {
            $usuario->nombre = $validated['nombre'];
        }
        if (isset($validated['email'])) {
            $usuario->email = $validated['email'];
        }
        if (isset($validated['tel'])) {
            $usuario->tel = $validated['tel'];
        }
        if (isset($validated['password'])) {
            $usuario->password = bcrypt($validated['password']);
        }
        if (isset($validated['anonimo'])) {
            $usuario->anonimo = $validated['anonimo'];
        }

        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente',
            'data' => $usuario
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }

    /**
     * Get current user information
     */
    public function usuario(Request $request)
    {
        // This would typically get the authenticated user
        // For now, return a placeholder response
        return response()->json([
            'success' => true,
            'message' => 'Endpoint para obtener información del usuario actual'
        ]);
    }

    /**
     * User registration
     */
    public function Registro(Request $request)
    {
        return $this->store($request);
    }
}
