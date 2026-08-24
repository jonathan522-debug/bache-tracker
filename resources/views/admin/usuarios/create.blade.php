<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Usuario - Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6 flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Registrar Nuevo Usuario</h1>
            <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-gray-500 hover:underline">← Volver a la lista</a>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" required class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('nombre') }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Apellidos</label>
                    <input type="text" name="apellidos" required class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('apellidos') }}">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" name="correo" required class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('correo') }}">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700">Contraseña Temporal</label>
                <input type="password" name="password" required class="mt-1 w-full p-2 border rounded-lg text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700">Cédula de Identidad</label>
                    <input type="text" name="cedula_identidad" required class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('cedula_identidad') }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('telefono') }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700">Rol del Sistema</label>
                    <select name="rol_id" required class="mt-1 w-full p-2 border rounded-lg text-sm bg-white">
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->rol }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Género</label>
                    <select name="genero_id" required class="mt-1 w-full p-2 border rounded-lg text-sm bg-white">
                        @foreach($generos as $genero)
                            <option value="{{ $genero->id }}">{{ $genero->genero }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm mt-4">
                Completar Registro
            </button>
        </form>
    </div>

</body>
</html>