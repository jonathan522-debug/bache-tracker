<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Reportes - Gestión Municipal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Todos los Reportes</h1>
                <p class="text-gray-600 text-sm">Reportes ciudadanos registrados en el sistema</p>
            </div>
            <a href="{{ url('/baches') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">Volver</a>
        </div>

        @if($reportes->isEmpty())
            <div class="bg-white p-8 rounded-xl shadow-md text-center text-gray-500">
                Todavía no hay reportes registrados.
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="p-3">Foto</th>
                        <th class="p-3">Ciudadano</th>
                        <th class="p-3">Ubicación</th>
                        <th class="p-3">Descripción</th>
                        <th class="p-3">Estado del bache</th>
                        <th class="p-3">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($reportes as $reporte)
                        @php
                            $evidencia = $reporte->evidencias->first();
                            $estado = $reporte->bache?->estado?->estado ?? 'Desconocido';
                            $colorEstado = match($estado) {
                                'Reportado' => 'bg-amber-100 text-amber-700',
                                'Verificado' => 'bg-blue-100 text-blue-700',
                                'En Planificación', 'En Reparación' => 'bg-indigo-100 text-indigo-700',
                                'Reparado' => 'bg-green-100 text-green-700',
                                'Rechazado' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="p-3">
                                @if($evidencia)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($evidencia->ruta_imagen) }}" alt="Evidencia" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 flex items-center justify-center text-gray-400 text-[10px] bg-gray-100 rounded-lg">Sin foto</div>
                                @endif
                            </td>
                            <td class="p-3">
                                <p class="font-medium text-gray-800">{{ $reporte->ciudadano?->nombre }} {{ $reporte->ciudadano?->apellidos }}</p>
                                <p class="text-xs text-gray-500">{{ $reporte->ciudadano?->correo }}</p>
                            </td>
                            <td class="p-3 text-gray-600">
                                <p>{{ $reporte->bache?->calle ?? 'Calle no especificada' }}</p>
                                <p class="text-xs text-gray-400">{{ $reporte->bache?->referencia ?? 'Sin referencia' }}</p>
                            </td>
                            <td class="p-3 text-gray-600 max-w-xs">{{ $reporte->descripcion ?? '—' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colorEstado }}">{{ $estado }}</span>
                            </td>
                            <td class="p-3 text-xs text-gray-400 whitespace-nowrap">{{ $reporte->fecha->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
