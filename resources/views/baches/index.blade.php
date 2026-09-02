@extends('layouts.app')

@section('content')
<div x-data="bacheApp()" x-init="initMap()" class="relative h-screen w-screen overflow-hidden bg-slate-100 font-sans">

    <!-- Cabecera flotante -->
    <header class="absolute top-4 left-4 right-4 z-30 flex flex-row items-start justify-between gap-3 pointer-events-none">

        <!-- Lado Izquierdo: Título -->
        <div class="bg-white/90 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-lg border border-emerald-100 flex items-center justify-between w-auto pointer-events-auto">
            <div class="flex items-center gap-2">
                <div class="bg-emerald-600 text-white p-2 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h1 class="font-bold text-slate-800 leading-none">Baches SCZ</h1>
                    <span class="text-xs font-semibold text-emerald-600">Santa Cruz</span>
                </div>
            </div>
        </div>

        <!-- Lado Derecho: Menú de Usuario -->
        <div class="pointer-events-auto flex items-center gap-3">
            @if(Auth::user()?->rol?->rol !== 'Administrador')
            <div class="bg-slate-900/80 backdrop-blur-md text-white px-4 py-2.5 rounded-xl shadow-lg text-xs font-medium hidden md:flex items-center gap-2 border border-slate-700">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
                <span>Clic en el mapa para marcar</span>
            </div>
            @endif

            <!-- Menú Desplegable Alpine -->
            <div x-data="{ menuOpen: false }" class="relative">
                <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false"
                        class="bg-white/90 backdrop-blur-md px-3 py-2 rounded-2xl shadow-lg border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition active:scale-95">
                    <div class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold uppercase">
                        {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                    </div>
                    <span class="font-bold text-slate-700 text-sm hidden sm:block">
                        {{ explode(' ', Auth::user()->nombre ?? 'Usuario')[0] }}
                    </span>
                    <svg class="w-4 h-4 text-slate-500" :class="{'rotate-180': menuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="menuOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-[-10px]"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-[-10px]"
                     class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden py-2"
                     style="display: none;">

                    <a href="{{ route('perfil.index') }}" class="group block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Mi Perfil
                    </a>

                    @if(Auth::user()?->rol?->rol !== 'Administrador')
                    <a href="{{ route('reportes.personales') }}" class="group block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Mis Reportes
                    </a>
                    @endif

                    @if(Auth::user()?->rol?->rol !== 'Ciudadano')
                        <a href="{{ route('gestion.verificaciones.index') }}" class="group block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Verificar Baches
                        </a>
                        <a href="{{ route('gestion.reportes.index') }}" class="group block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Todos los Reportes
                        </a>
                        <a href="{{ route('gestion.planes.index') }}" class="group block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.818V8.052a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            Planes de Acción
                        </a>
                    @endif

                    @if(Auth::user()?->rol?->rol === 'Administrador')
                        <a href="{{ route('admin.usuarios.index') }}" class="group block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Panel de Administración
                        </a>
                    @endif

                    <hr class="my-1 border-slate-100">

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="group w-full text-left px-4 py-3 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenedor del Mapa -->
    <div id="map" class="h-full w-full z-10 cursor-crosshair"></div>

    <!-- Botón Flotante Inferior -->
    @if(Auth::user()?->rol?->rol !== 'Administrador')
    <div x-show="!drawerOpen" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20">
        <button @click="abrirFlujoSinPunto()"
                class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold px-7 py-4 rounded-full shadow-2xl flex items-center gap-3 transition-all duration-200 group border-2 border-emerald-400/30">
            <span class="bg-emerald-500/80 p-1.5 rounded-full group-hover:rotate-90 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </span>
            <span class="text-base tracking-wide">Reportar bache aquí</span>
        </button>
    </div>
    @endif

    <!-- Panel Lateral Fix Móvil -->
    <div x-show="drawerOpen"
         x-transition:enter="transition transform ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition transform ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 w-full md:w-[420px] h-dvh max-h-dvh bg-white z-40 shadow-2xl rounded-l-3xl flex flex-col border-l border-slate-100 overflow-hidden"
         style="display: none;">

        <div class="p-5 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div>
                <h2 class="font-bold text-slate-800 text-lg">Reportar nuevo bache</h2>
                <p class="text-xs text-slate-400">Paso <span x-text="paso"></span> de 2</p>
            </div>
            <button @click="cerrarFlujo()" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 flex-1 overflow-y-auto">

            <div class="bg-emerald-50 border border-emerald-200 p-3.5 rounded-2xl mb-5 flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <div class="text-xs">
                    <p class="font-bold text-emerald-900">Ubicación Seleccionada:</p>
                    <p class="text-emerald-700 font-medium mt-0.5" x-text="ubicacionTexto"></p>
                    <p class="text-slate-400 text-[10px] mt-0.5" x-text="coordenadasTexto"></p>
                </div>
            </div>

            <!-- PASO 1: Subir Foto -->
            <div x-show="paso === 1" class="space-y-5">
                <p class="text-sm text-slate-600">Adjunta una fotografía real para verificar la dimensión del bache.</p>

                <input type="file" x-ref="fotoInput" @change="cargarFotoReal" accept="image/*" class="hidden">

                <div @click="$refs.fotoInput.click()"
                     class="border-2 border-dashed border-emerald-300 bg-emerald-50/50 hover:bg-emerald-50 rounded-2xl p-6 text-center cursor-pointer transition flex flex-col items-center justify-center gap-3 group">

                    <template x-if="!fotoCargada">
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-emerald-100 text-emerald-600 p-4 rounded-full group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-89l0.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l0.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="font-semibold text-emerald-900 text-sm">Haz clic para adjuntar foto</span>
                            <span class="text-xs text-slate-400">JPG, PNG hasta 10MB</span>
                        </div>
                    </template>

                    <template x-if="fotoCargada">
                        <div class="relative w-full h-40 rounded-xl overflow-hidden shadow-md">
                            <img :src="fotoPreview" class="w-full h-full object-cover" alt="Bache fotografiado">
                            <div class="absolute top-2 right-2 bg-emerald-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Foto Lista
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- PASO 2: Detalles -->
            <div x-show="paso === 2" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nombre Completo (Confirmación)</label>
                    <input type="text" x-model="form.nombre" readonly class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm focus:outline-none text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Teléfono / WhatsApp</label>
                    <input type="text" x-model="form.telefono" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Referencia adicional (Obligatorio)</label>
                    <textarea x-model="form.referencia" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 transition" placeholder="Ej: Frente al surtidor, carril derecho..."></textarea>
                </div>
            </div>

        </div>

        <!-- Controles Inferiores -->
        <div class="p-5 border-t border-slate-100 bg-white shrink-0">
            <template x-if="paso === 1">
                <button @click="paso = 2"
                        :disabled="!fotoCargada"
                        :class="fotoCargada ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                        class="w-full py-3.5 rounded-xl font-bold transition shadow-lg text-sm">
                    Siguiente
                </button>
            </template>

            <template x-if="paso === 2">
                <div class="flex gap-2">
                    <button @click="paso = 1" :disabled="cargando" class="w-1/3 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition">
                        Atrás
                    </button>
                    <button @click="finalizarReporte()"
                            :disabled="cargando"
                            :class="cargando ? 'bg-emerald-400 cursor-wait' : 'bg-emerald-600 hover:bg-emerald-700'"
                            class="w-2/3 py-3.5 text-white font-bold rounded-xl shadow-lg text-sm transition flex justify-center items-center gap-2">
                        <span x-show="cargando" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                        <span x-text="cargando ? 'Enviando...' : 'Enviar Reporte'"></span>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal de Éxito -->
    <div x-show="modalExito"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         style="display: none;">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full text-center shadow-2xl space-y-4">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">¡Reporte Guardado!</h3>
            <p class="text-xs text-slate-500">Se fijó la posición y se actualizó la información en el mapa.</p>
            <button @click="cerrarModalExito()" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-sm shadow-md hover:bg-emerald-700 transition">
                Volver al Mapa
            </button>
        </div>
    </div>

</div>

<script>
function bacheApp() {
    return {
        map: null,
        tempMarker: null,
        selectedLat: -17.7833,
        selectedLng: -63.1821,
        ubicacionTexto: 'Av. Cristóbal de Mendoza, Santa Cruz',
        coordenadasTexto: '-17.7833, -63.1821',
        drawerOpen: false,
        paso: 1,
        fotoCargada: false,
        fotoArchivo: null,
        fotoPreview: null,
        cargando: false,
        modalExito: false,
        esAdmin: {{ Auth::user()?->rol?->rol === 'Administrador' ? 'true' : 'false' }},
        form: {
            nombre: '{{ Auth::user()->nombre ?? "Usuario" }}',
            telefono: '{{ Auth::user()->telefono ?? "" }}',
            referencia: ''
        },

        initMap() {
            this.map = L.map('map', { zoomControl: true }).setView([-17.7833, -63.1821], 13);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(this.map);

            const bachesReales = {!! json_encode($baches) !!};

            const clusterGroup = L.markerClusterGroup({
                maxClusterRadius: 45,
                iconCreateFunction: (cluster) => {
                    const markers = cluster.getAllChildMarkers();
                    let totalReportes = 0;

                    markers.forEach(m => {
                        totalReportes += (m.options.reportesCount || 1);
                    });

                    let colorClase = 'bg-amber-400/80 text-amber-950 shadow-amber-400/40';
                    if (totalReportes >= 3 && totalReportes < 5) {
                        colorClase = 'bg-orange-500/85 text-white shadow-orange-500/40';
                    } else if (totalReportes >= 5) {
                        colorClase = 'bg-rose-600/90 text-white shadow-rose-600/50 animate-pulse';
                    }

                    return L.divIcon({
                        className: 'custom-cluster-pin',
                        html: `<div class="${colorClase} w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center shadow-lg transition-transform hover:scale-125">${totalReportes}</div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });
                }
            });

            bachesReales.forEach(bache => {
                const totalReportes = bache.reportes_count || 1;

                let colorClase = 'bg-amber-400/80 text-amber-950 shadow-amber-400/40';
                if (totalReportes >= 3 && totalReportes < 5) {
                    colorClase = 'bg-orange-500/85 text-white shadow-orange-500/40';
                } else if (totalReportes >= 5) {
                    colorClase = 'bg-rose-600/90 text-white shadow-rose-600/50 animate-pulse';
                }

                const icon = L.divIcon({
                    className: 'custom-pin',
                    html: `<div class="${colorClase} w-6 h-6 rounded-full font-bold text-[11px] flex items-center justify-center shadow-lg transition-transform hover:scale-125">${totalReportes}</div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                const marker = L.marker([bache.latitud, bache.longitud], {
                    icon: icon,
                    reportesCount: totalReportes
                }).bindPopup(`
                    <div class="text-sm">
                        <b>Bache #${bache.id}</b><br>
                        <span class="text-xs font-semibold text-rose-600">${totalReportes} reporte(s) registrado(s)</span><br>
                        <span class="text-xs text-gray-500">${bache.referencia || 'Sin referencia'}</span>
                    </div>
                `);

                clusterGroup.addLayer(marker);
            });

            this.map.addLayer(clusterGroup);

            this.map.on('click', (e) => {
                if (this.esAdmin) return;
                this.seleccionarUbicacion(e.latlng.lat, e.latlng.lng);
            });
        },

        seleccionarUbicacion(lat, lng) {
            this.selectedLat = lat;
            this.selectedLng = lng;
            this.coordenadasTexto = `GPS: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            this.ubicacionTexto = `Punto seleccionado en el mapa`;

            if (this.tempMarker) {
                this.map.removeLayer(this.tempMarker);
            }

            const pinIcon = L.divIcon({
                className: 'temp-pin',
                html: `<div class="bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-xl animate-bounce"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            this.tempMarker = L.marker([lat, lng], { icon: pinIcon }).addTo(this.map);

            this.drawerOpen = true;
            this.paso = 1;

            this.fotoCargada = false;
            this.fotoArchivo = null;
            this.fotoPreview = null;
            this.form.referencia = '';
        },

        abrirFlujoSinPunto() {
            this.seleccionarUbicacion(-17.7833, -63.1821);
        },

        cerrarFlujo() {
            this.drawerOpen = false;
            if (this.tempMarker) {
                this.map.removeLayer(this.tempMarker);
            }
        },

        cargarFotoReal(event) {
            const file = event.target.files[0];
            if (file) {
                this.fotoArchivo = file;
                this.fotoPreview = URL.createObjectURL(file);
                this.fotoCargada = true;
            }
        },

        async finalizarReporte() {
            if (!this.form.referencia) {
                alert("Por favor, ingresa una referencia para ayudar a ubicar el bache.");
                return;
            }

            this.cargando = true;

            let formData = new FormData();
            formData.append('latitud', this.selectedLat);
            formData.append('longitud', this.selectedLng);
            formData.append('referencia', this.form.referencia);
            formData.append('foto', this.fotoArchivo);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                let response = await fetch('{{ route("baches.store") }}', {
                    method: 'POST',
                    body: formData
                });

                let result = await response.json();

                if (response.ok) {
                    this.drawerOpen = false;
                    this.modalExito = true;

                    if (this.tempMarker) {
                        this.map.removeLayer(this.tempMarker);
                    }
                } else {
                    alert('Error del servidor: ' + (result.message || 'No se pudo guardar el reporte.'));
                }
            } catch (error) {
                console.error("Error enviando el reporte", error);
                alert("Ocurrió un error de conexión al enviar el reporte.");
            } finally {
                this.cargando = false;
            }
        },

        cerrarModalExito() {
            this.modalExito = false;
            window.location.reload();
        }
    }
}
</script>
@endsections
