@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Mi Perfil</span>
            </h2>
            <a href="{{ route('baches.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-200 px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Volver</span>
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 space-y-8">
            
            <!-- Identidad del Usuario -->
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-4xl font-bold mb-3 shadow-inner">
                    {{ substr($user->nombre ?? 'U', 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-slate-800">{{ $user->nombre ?? 'Usuario' }} {{ $user->apellidos ?? '' }}</h3>
                <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1 rounded-full mt-1">Ciudadano Registrado</span>
            </div>

            <!-- Panel de Métricas / Estadísticas -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- Puntos -->
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col items-center text-center">
                    <div class="p-2 bg-amber-100 text-amber-600 rounded-xl mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-extrabold text-slate-800">{{ $user->puntos ?? 0 }}</span>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Puntos</span>
                </div>

                <!-- Reportes Sin Verificar -->
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col items-center text-center">
                    <div class="p-2 bg-amber-100 text-amber-600 rounded-xl mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-extrabold text-slate-800">{{ $reportesSinVerificar ?? 0 }}</span>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Sin Verificar</span>
                </div>

                <!-- Reportes Verificados -->
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col items-center text-center">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-xl mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-extrabold text-slate-800">{{ $reportesVerificados ?? 0 }}</span>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Verificados</span>
                </div>

            </div>

            <!-- Formulario de Edición -->
            <form action="{{ route('perfil.update') }}" method="POST" class="space-y-5 pt-2">
                @csrf
                @method('PUT')

                <!-- Campo Correo -->
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        <span>Correo Electrónico</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </label>
                    <input type="email" name="correo" value="{{ old('correo', $user->correo) }}" readonly
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 font-medium focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                    @error('correo')
                        <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Teléfono -->
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        <span>Teléfono / WhatsApp</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </label>
                    <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" placeholder="Ej: 71234567"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 font-medium focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                    @error('telefono')
                        <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Miembro desde -->
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        <span>Miembro desde</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </label>
                    <div class="px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-600 font-medium text-sm">
                        {{ $user->created_at ? $user->created_at->locale('es')->isoFormat('D [de] MMMM, YYYY') : 'N/A' }}
                    </div>
                </div>

                <!-- Botón Guardar -->
                <button type="submit" class="w-full mt-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-600/10 transition active:scale-[0.99] flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Guardar Cambios</span>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection