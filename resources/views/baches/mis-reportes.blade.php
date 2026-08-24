<<<<<<< Updated upstream
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
=======
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <span>📋</span> Mis Reportes de Baches
            </h2>
            <a href="{{ route('baches.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-4 py-2 rounded-lg transition">
                Volver al Mapa
            </a>
        </div>

        @if($reportes->isEmpty())
            <div class="bg-white rounded-2xl p-10 text-center shadow-sm border border-slate-100">
                <div class="text-4xl mb-4">🛣️</div>
                <h3 class="text-lg font-bold text-slate-700">Aún no has reportado baches</h3>
                <p class="text-slate-500 mt-2 text-sm">Tus reportes ciudadanos aparecerán aquí.</p>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($reportes as $reporte)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-xs font-bold text-slate-400">
                                    {{ $reporte->created_at->format('d M, Y') }}
                                </span>
                                
                                <!-- Insignia de Estado -->
                                @if($reporte->bache->estado_id == 1)
                                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">Pendiente</span>
                                @elseif($reporte->bache->estado_id == 2)
                                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">En Revisión</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">Reparado</span>
                                @endif
                            </div>
                            
                            <h3 class="font-bold text-slate-700 mb-1">Bache #{{ $reporte->bache_id }}</h3>
                            <p class="text-sm text-slate-500 line-clamp-2">{{ $reporte->bache->referencia ?? 'Sin referencia adicional' }}</p>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-slate-50 flex items-center gap-2 text-xs text-slate-400">
                            <span>📍</span> {{ number_format($reporte->bache->latitud, 4) }}, {{ number_format($reporte->bache->longitud, 4) }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
>>>>>>> Stashed changes
