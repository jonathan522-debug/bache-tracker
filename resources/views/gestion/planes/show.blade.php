<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plan->nombre }} - Plan de Acción</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $plan->nombre }}</h1>
                <p class="text-gray-600 text-sm">{{ $plan->descripcion ?? 'Sin descripción' }}</p>
                <p class="text-xs text-gray-400 mt-1">A cargo de {{ $plan->funcionario?->nombre }} {{ $plan->funcionario?->apellidos }} · Creado {{ $plan->fecha_creacion?->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('gestion.planes.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">Volver</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Estado del plan -->
        <div class="bg-white p-5 rounded-xl shadow-md mb-6 flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-700">Estado del plan</span>
            <form action="{{ route('gestion.planes.updateEstado', $plan) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="estado" class="p-2 border rounded-lg text-sm bg-white">
                    <option value="Borrador" {{ $plan->estado == 'Borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="En Progreso" {{ $plan->estado == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                    <option value="Finalizado" {{ $plan->estado == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                </select>
                <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded-lg text-xs hover:bg-gray-700">Actualizar</button>
            </form>
        </div>

        <!-- Baches en el plan -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto mb-6">
            <h2 class="text-lg font-semibold text-gray-800 p-5 pb-0">Baches en este plan</h2>
            <table class="w-full text-left border-collapse text-sm mt-3">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="p-3">Ubicación</th>
                        <th class="p-3">Prioridad</th>
                        <th class="p-3">Fecha estimada</th>
                        <th class="p-3">Observación</th>
                        <th class="p-3">Estado actual</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($plan->detalles as $detalle)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">
                                <p class="font-medium text-gray-800">{{ $detalle->bache?->calle ?? 'Calle no especificada' }}</p>
                                <p class="text-xs text-gray-500">{{ $detalle->bache?->referencia ?? 'Sin referencia' }}</p>
                            </td>
                            <td class="p-3 text-gray-600">{{ $detalle->prioridad }}</td>
                            <td class="p-3 text-gray-600">{{ $detalle->fecha_estimada?->format('d/m/Y') ?? '—' }}</td>
                            <td class="p-3 text-gray-600">{{ $detalle->observacion ?? '—' }}</td>
                            <td class="p-3 text-gray-600">{{ $detalle->bache?->estado?->estado }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Todavía no se agregaron baches a este plan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Agregar bache -->
        <div class="bg-white rounded-xl shadow-md p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Agregar bache verificado</h2>

            @if($bachesDisponibles->isEmpty())
                <p class="text-sm text-gray-500">No hay baches verificados disponibles para planificar en este momento.</p>
            @else
                <form action="{{ route('gestion.planes.baches.store', $plan) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Bache</label>
                        <select name="bache_id" required class="w-full p-2 border rounded-lg text-sm bg-white">
                            @foreach($bachesDisponibles as $bache)
                                <option value="{{ $bache->id }}">
                                    {{ $bache->calle ?? 'Calle no especificada' }} — {{ $bache->referencia ?? 'Sin referencia' }} ({{ number_format($bache->latitud, 4) }}, {{ number_format($bache->longitud, 4) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Prioridad</label>
                        <input type="number" name="prioridad" min="1" value="1" required class="w-full p-2 border rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Fecha estimada</label>
                        <input type="date" name="fecha_estimada" class="w-full p-2 border rounded-lg text-sm">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Observación</label>
                        <input type="text" name="observacion" class="w-full p-2 border rounded-lg text-sm" placeholder="Opcional">
                    </div>

                    <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                        Agregar al plan
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
