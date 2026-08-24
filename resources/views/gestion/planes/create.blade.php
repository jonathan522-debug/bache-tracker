<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Plan de Acción - Gestión Municipal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6 flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nuevo Plan de Acción</h1>
            <a href="{{ route('gestion.planes.index') }}" class="text-sm text-gray-500 hover:underline">← Volver a la lista</a>
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

        <form action="{{ route('gestion.planes.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-700">Nombre del plan</label>
                <input type="text" name="nombre" required class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('nombre') }}">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700">Descripción</label>
                <textarea name="descripcion" rows="3" class="mt-1 w-full p-2 border rounded-lg text-sm">{{ old('descripcion') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('fecha_inicio') }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Fecha de fin (estimada)</label>
                    <input type="date" name="fecha_fin" class="mt-1 w-full p-2 border rounded-lg text-sm" value="{{ old('fecha_fin') }}">
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition font-medium text-sm mt-4">
                Crear Plan
            </button>
        </form>
    </div>

</body>
</html>
