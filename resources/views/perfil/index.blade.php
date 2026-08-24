@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <span>👤</span> Mi Perfil
            </h2>
            <a href="{{ route('baches.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-200 px-4 py-2 rounded-lg transition shadow-sm">
                Volver
            </a>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
            <div class="flex flex-col items-center mb-8">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-4xl font-bold mb-4 shadow-inner">
                    {{ substr($user->nombre ?? 'U', 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-slate-800">{{ $user->nombre ?? 'Usuario' }}</h3>
                <span class="bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full mt-2">Ciudadano Registrado</span>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-4">
                    <div class="text-xl">✉️</div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Correo Electrónico</p>
                        <p class="text-slate-700 font-medium">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-4">
                    <div class="text-xl">📱</div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Teléfono</p>
                        <p class="text-slate-700 font-medium">{{ $user->telefono ?? 'No registrado' }}</p>
                    </div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-4">
                    <div class="text-xl">📅</div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Miembro desde</p>
                        <p class="text-slate-700 font-medium">{{ $user->created_at->format('d de F, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection