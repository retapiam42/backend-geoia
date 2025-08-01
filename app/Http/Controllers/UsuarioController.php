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
        return response()->json($usuarios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used in API
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'anonimo' => 'required|boolean',
        ]);

        $usuario = Usuario::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'anonimo' => $validated['anonimo'],
        ]);

        return response()->json($usuario, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return response()->json($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        // Not used in API
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:usuarios,email,' . $usuario->usuarios_id . ',usuarios_id',
            'password' => 'sometimes|required|string|min:6',
            'anonimo' => 'sometimes|required|boolean',
        ]);

        if (isset($validated['nombre'])) {
            $usuario->nombre = $validated['nombre'];
        }
        if (isset($validated['email'])) {
            $usuario->email = $validated['email'];
        }
        if (isset($validated['password'])) {
            $usuario->password = bcrypt($validated['password']);
        }
        if (isset($validated['anonimo'])) {
            $usuario->anonimo = $validated['anonimo'];
        }

        $usuario->save();

        return response()->json($usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json(null, 204);
    }
}
