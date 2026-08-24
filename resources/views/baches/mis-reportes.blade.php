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
                    @php
                        $estado = $reporte->bache?->estado?->estado ?? 'Reportado';
                        $badge = match($estado) {
                            'Reportado' => ['bg-amber-100 text-amber-700', 'Pendiente'],
                            'Verificado' => ['bg-blue-100 text-blue-700', 'En Revisión'],
                            'En Planificación', 'En Reparación' => ['bg-indigo-100 text-indigo-700', 'En Proceso'],
                            'Reparado' => ['bg-emerald-100 text-emerald-700', 'Reparado'],
                            'Rechazado' => ['bg-rose-100 text-rose-700', 'Rechazado'],
                            default => ['bg-slate-100 text-slate-600', $estado],
                        };
                    @endphp
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-xs font-bold text-slate-400">
                                    {{ $reporte->created_at->format('d M, Y') }}
                                </span>

                                <span class="{{ $badge[0] }} text-xs font-bold px-2.5 py-1 rounded-full">{{ $badge[1] }}</span>
                            </div>

                            <h3 class="font-bold text-slate-700 mb-1">Bache #{{ $reporte->bache_id }}</h3>
                            <p class="text-sm text-slate-500 line-clamp-2">{{ $reporte->bache?->referencia ?? 'Sin referencia adicional' }}</p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-50 flex items-center gap-2 text-xs text-slate-400">
                            <span>📍</span> {{ number_format($reporte->bache?->latitud ?? 0, 4) }}, {{ number_format($reporte->bache?->longitud ?? 0, 4) }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
