<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Panel de Administración</h1>
                <p class="text-gray-600 text-sm">Gestión de cuentas y control de accesos del personal</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.usuarios.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">+ Añadir Usuario</a>
                <a href="{{ url('/baches') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">Volver</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla de Visualización -->
        <div class="bg-white p-6 rounded-xl shadow-md overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Lista de Usuarios Registrados</h2>
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="p-3">Nombre Completo</th>
                        <th class="p-3">Correo</th>
                        <th class="p-3">Rol Actual</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3 text-center">Modificar Rol</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($usuarios as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-800">{{ $user->nombre }} {{ $user->apellidos }}</td>
                            <td class="p-3 text-gray-600">{{ $user->correo }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->rol->rol == 'Administrador' ? 'bg-purple-100 text-purple-700' : ($user->rol->rol == 'Funcionario' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $user->rol->rol ?? 'Sin Rol' }}
                                </span>
                            </td>
                            <td class="p-3">
                                @if($user->activo)
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Inactivo (Baja)</span>
                                @endif
                            </td>
                            <!-- Formulario rápido para cambiar rol en línea -->
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.usuarios.updateRole', $user->id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="rol_id" class="p-1 border rounded text-xs bg-white">
                                        <option value="1" {{ $user->rol_id == 1 ? 'selected' : '' }}>Ciudadano</option>
                                        <option value="2" {{ $user->rol_id == 2 ? 'selected' : '' }}>Funcionario</option>
                                        <option value="3" {{ $user->rol_id == 3 ? 'selected' : '' }}>Administrador</option>
                                    </select>
                                    <button type="submit" class="bg-gray-800 text-white px-2 py-1 rounded text-xs hover:bg-gray-700">Cambiar</button>
                                </form>
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.usuarios.toggle', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="px-3 py-1 text-xs rounded-lg transition font-medium 
                                        {{ $user->activo ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' : 'bg-green-50 text-green-600 border border-green-200 hover:bg-green-100' }}">
                                        {{ $user->activo ? 'Dar de Baja' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>