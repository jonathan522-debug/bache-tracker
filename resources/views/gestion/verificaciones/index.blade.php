<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Baches - Gestión Municipal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-6xl mx-auto">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Verificación de Baches</h1>
                <p class="text-gray-600 text-sm">Baches reportados pendientes de confirmar existencia y severidad</p>
            </div>
            <a href="{{ url('/baches') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">Volver</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($baches->isEmpty())
            <div class="bg-white p-8 rounded-xl shadow-md text-center text-gray-500">
                No hay baches pendientes de verificación.
            </div>
        @endif

        <div class="space-y-4">
            @foreach($baches as $bache)
                @php
                    $evidencia = $bache->reportes->flatMap->evidencias->first();
                    $reporte = $bache->reportes->first();
                @endphp
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-56 shrink-0 bg-gray-100">
                            @if($evidencia)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($evidencia->ruta_imagen) }}" alt="Evidencia del bache" class="w-full h-40 md:h-full object-cover">
                            @else
                                <div class="w-full h-40 md:h-full flex items-center justify-center text-gray-400 text-xs">Sin foto</div>
                            @endif
                        </div>

                        <div class="p-5 flex-1">
                            <div class="flex flex-wrap justify-between gap-2 mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $bache->calle ?? 'Calle no especificada' }}</p>
                                    <p class="text-xs text-gray-500">{{ $bache->referencia ?? 'Sin referencia adicional' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">GPS: {{ number_format($bache->latitud, 5) }}, {{ number_format($bache->longitud, 5) }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-400">
                                    <p>Reportado: {{ optional($reporte?->fecha ?? $bache->created_at)->format('d/m/Y H:i') }}</p>
                                    <p>{{ $bache->reportes->count() }} reporte(s) asociado(s)</p>
                                </div>
                            </div>

                            @if($reporte?->descripcion)
                                <p class="text-sm text-gray-600 mb-3">"{{ $reporte->descripcion }}"</p>
                            @endif

                            <form action="{{ route('gestion.verificaciones.store', $bache->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end border-t pt-4 mt-2">
                                @csrf
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">¿Existe el bache?</label>
                                    <select name="existencia" class="w-full p-2 border rounded-lg text-sm" required>
                                        <option value="1">Sí, existe</option>
                                        <option value="0">No existe</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Severidad</label>
                                    <select name="severidad_id" class="w-full p-2 border rounded-lg text-sm">
                                        <option value="">— No aplica —</option>
                                        @foreach($severidades as $severidad)
                                            <option value="{{ $severidad->id }}">{{ $severidad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-1">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Observación</label>
                                    <input type="text" name="observacion" class="w-full p-2 border rounded-lg text-sm" placeholder="Opcional">
                                </div>

                                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                                    Registrar verificación
                                </button>
                            </form>
                            @error('severidad_id')
                                <p class="text-red-600 text-xs mt-2">La severidad es obligatoria cuando el bache existe.</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
