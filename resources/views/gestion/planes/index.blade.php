<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes de Acción - Gestión Municipal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Planes de Acción</h1>
                <p class="text-gray-600 text-sm">Agrupa baches verificados para planificar su reparación</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('gestion.planes.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-sm font-medium">+ Nuevo Plan</a>
                <a href="{{ url('/baches') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">Volver</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="p-3">Nombre</th>
                        <th class="p-3">Funcionario a cargo</th>
                        <th class="p-3">Baches</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3">Creado</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($planes as $plan)
                        @php
                            $colorEstado = match($plan->estado) {
                                'Borrador' => 'bg-gray-100 text-gray-700',
                                'En Progreso' => 'bg-blue-100 text-blue-700',
                                'Finalizado' => 'bg-green-100 text-green-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-800">{{ $plan->nombre }}</td>
                            <td class="p-3 text-gray-600">{{ $plan->funcionario?->nombre }} {{ $plan->funcionario?->apellidos }}</td>
                            <td class="p-3 text-gray-600">{{ $plan->detalles_count }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colorEstado }}">{{ $plan->estado }}</span>
                            </td>
                            <td class="p-3 text-xs text-gray-400">{{ $plan->fecha_creacion?->format('d/m/Y H:i') }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('gestion.planes.show', $plan) }}" class="text-emerald-600 hover:underline text-sm font-medium">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">Todavía no hay planes de acción creados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
