<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Rol;
use App\Models\Genero;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 1. Listar usuarios (Visualización principal)
     */
    public function index()
    {
        $usuarios = User::with(['rol', 'genero'])->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }
    public function perfil()
    {
        $user = Auth::user();
        return view('perfil.index', compact('user'));
    }

    /**
     * 2. Mostrar formulario de creación por separado
     */
    public function create()
    {
        $roles = Rol::all();
        $generos = Genero::all();
        return view('admin.usuarios.create', compact('roles', 'generos'));
    }

    /**
     * 3. Almacenar el nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'unique:users,correo'],
            'password' => ['required', 'min:6'],
            'rol_id' => ['required', 'exists:roles,id'],
            'genero_id' => ['required', 'exists:generos,id'],
            'cedula_identidad' => ['required', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
            'genero_id' => $request->genero_id,
            'cedula_identidad' => $request->cedula_identidad,
            'telefono' => $request->telefono,
            'activo' => true,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * 4. Modificar el rol de un usuario existente
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'rol_id' => ['required', 'exists:roles,id']
        ]);

        $user = User::findOrFail($id);
        $user->rol_id = $request->rol_id;
        $user->save();

        return redirect()->route('admin.usuarios.index')->with('success', 'Rol de usuario actualizado correctamente.');
    }

    /**
     * 5. Dar de baja o reactivar
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->activo = !$user->activo;
        $user->save();

        $estadoTexto = $user->activo ? 'reactivado' : 'dado de baja';
        return redirect()->route('admin.usuarios.index')->with('success', "El usuario ha sido {$estadoTexto} correctamente.");
    }
}