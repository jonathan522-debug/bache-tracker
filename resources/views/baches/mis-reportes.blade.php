<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reportes - Baches SCZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Mis Reportes</h1>
                <p class="text-gray-600 text-sm">Baches que has reportado y su estado actual</p>
            </div>
            <a href="{{ url('/baches') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">Volver al mapa</a>
        </div>

        @if($reportes->isEmpty())
            <div class="bg-white p-8 rounded-xl shadow-md text-center text-gray-500">
                Todavía no has reportado ningún bache.
            </div>
        @endif

        <div class="space-y-4">
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
                <div class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col md:flex-row">
                    <div class="md:w-48 shrink-0 bg-gray-100">
                        @if($evidencia)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($evidencia->ruta_imagen) }}" alt="Evidencia del bache" class="w-full h-36 md:h-full object-cover">
                        @else
                            <div class="w-full h-36 md:h-full flex items-center justify-center text-gray-400 text-xs">Sin foto</div>
                        @endif
                    </div>

                    <div class="p-5 flex-1">
                        <div class="flex flex-wrap justify-between gap-2 mb-2">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $reporte->bache?->calle ?? 'Calle no especificada' }}</p>
                                <p class="text-xs text-gray-500">{{ $reporte->bache?->referencia ?? 'Sin referencia adicional' }}</p>
                            </div>
                            <span class="px-2 py-1 h-fit text-xs font-semibold rounded-full {{ $colorEstado }}">{{ $estado }}</span>
                        </div>

                        <p class="text-sm text-gray-600 mb-2">{{ $reporte->descripcion }}</p>

                        <p class="text-xs text-gray-400">Reportado: {{ $reporte->fecha->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
