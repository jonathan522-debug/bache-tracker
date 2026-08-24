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

    <!-- Lado Derecho: Menú de Usuario (Navbar) -->
    <div class="pointer-events-auto flex items-center gap-3">
        
        <!-- Instrucción oculta en móviles -->
        <div class="bg-slate-900/80 backdrop-blur-md text-white px-4 py-2.5 rounded-xl shadow-lg text-xs font-medium hidden md:flex items-center gap-2 border border-slate-700">
            <span>🖱️ Clic en el mapa para marcar</span>
        </div>

        <!-- Menú Desplegable Alpine -->
        <div x-data="{ menuOpen: false }" class="relative">
            <!-- Botón del usuario -->
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

            <!-- Lista Desplegable -->
            <div x-show="menuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-[-10px]"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-[-10px]"
                 class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden py-2"
                 style="display: none;">
                
                <!-- Ver Perfil -->
                <a href="{{ route('perfil.index') }}" class="block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                    <span class="text-lg">👤</span> Mi Perfil
                </a>
                
                <!-- Mis Reportes -->
                <a href="{{ route('reportes.personales') }}" class="block px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                    <span class="text-lg">📋</span> Mis Reportes
                </a>
                
                <hr class="my-1 border-slate-100">
                
                <!-- Cerrar Sesión (Debe ser un POST por seguridad en Laravel) -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition flex items-center gap-3">
                        <span class="text-lg">🚪</span> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

</header>

    <!-- Contenedor del Mapa -->
    <div id="map" class="h-full w-full z-10 cursor-crosshair"></div>

    <!-- Botón Flotante Inferior -->
    <div x-show="!drawerOpen" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20">
        <button @click="abrirFlujoSinPunto()" 
                class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold px-7 py-4 rounded-full shadow-2xl flex items-center gap-3 transition-all duration-200 group border-2 border-emerald-400/30">
            <span class="bg-emerald-500/80 p-1.5 rounded-full group-hover:rotate-90 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </span>
            <span class="text-base tracking-wide">Reportar bache aquí</span>
        </button>
    </div>

    <!-- Panel Lateral (Drawer) -->
    <div x-show="drawerOpen" 
         x-transition:enter="transition transform ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition transform ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="absolute top-0 right-0 w-full md:w-[420px] h-full bg-white z-30 shadow-2xl rounded-l-3xl flex flex-col border-l border-slate-100"
         style="display: none;">
        
        <div class="p-5 border-b border-slate-100 flex items-end-safe justify-between">
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
                <span class="text-emerald-600 font-bold text-lg">📍</span>
                <div class="text-xs">
                    <p class="font-bold text-emerald-900">Ubicación Seleccionada:</p>
                    <p class="text-emerald-700 font-medium mt-0.5" x-text="ubicacionTexto"></p>
                    <p class="text-slate-400 text-[10px] mt-0.5" x-text="coordenadasTexto"></p>
                </div>
            </div>

            <!-- PASO 1: Subir Foto -->
            <div x-show="paso === 1" class="space-y-5">
                <p class="text-sm text-slate-600">Adjunta una fotografía real para verificar la dimensión del bache.</p>
                
                <!-- Input oculto para archivo real -->
                <input type="file" x-ref="fotoInput" @change="cargarFotoReal" accept="image/*" class="hidden">

                <div @click="$refs.fotoInput.click()" 
                     class="border-2 border-dashed border-emerald-300 bg-emerald-50/50 hover:bg-emerald-50 rounded-2xl p-8 text-center cursor-pointer transition flex flex-col items-center justify-center gap-3 group">
                    
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
                        <div class="relative w-full h-48 rounded-xl overflow-hidden shadow-md">
                            <!-- Previsualización real de la imagen cargada -->
                            <img :src="fotoPreview" class="w-full h-full object-cover" alt="Bache fotografiado">
                            <div class="absolute top-2 right-2 bg-emerald-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow">
                                Foto Lista ✓
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
        <div class="p-5 border-t border-slate-100 bg-white">
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
                    <!-- El botón se deshabilita y cambia de texto mientras se envía -->
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
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full text-center shadow-2xl space-y-4 animate-bounce-short">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                ✓
            </div>
            <h3 class="font-bold text-slate-800 text-lg">¡Reporte Guardado!</h3>
            <p class="text-xs text-slate-500">Se fijó la posición y se guardó la evidencia en el sistema.</p>
            <button @click="modalExito = false" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-sm shadow-md hover:bg-emerald-700 transition">
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
        form: {
            nombre: '{{ Auth::user()->nombre ?? "Usuario" }}', 
            telefono: '{{ Auth::user()->telefono ?? "" }}',
            referencia: ''
        },

        initMap() {
            this.map = L.map('map', { zoomControl: true }).setView([-17.7833, -63.1821], 13);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(this.map);

            // 1. Cargamos los baches reales usando json_encode de forma segura
            const bachesReales = {!! json_encode($baches) !!};

            // 2. Iteramos sobre los baches reales para ponerlos en el mapa
            bachesReales.forEach(bache => {
                const icon = L.divIcon({
                    className: 'custom-pin',
                    // Icono de advertencia para baches reportados
                    html: `<div class="bg-rose-500 w-8 h-8 rounded-full border-2 border-white text-white font-bold text-sm flex items-center justify-center shadow-md">⚠️</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });
                
                L.marker([bache.latitud, bache.longitud], { icon: icon })
                .addTo(this.map)
                .bindPopup(`
                    <div class="text-sm">
                        <b>Bache #${bache.id}</b><br>
                        <span class="text-xs text-gray-500">${bache.referencia || 'Sin referencia'}</span>
                    </div>
                `);
            });

            this.map.on('click', (e) => {
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
                html: `<div class="bg-emerald-600 w-10 h-10 rounded-full border-2 border-white text-white font-bold text-lg flex items-center justify-center shadow-2xl animate-bounce">📍</div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
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

                    // Coloca el icono de "NUEVO"
                    const newIcon = L.divIcon({
                        className: 'final-pin',
                        html: `<div class="bg-emerald-600 w-9 h-9 rounded-full border-2 border-white text-white font-bold text-xs flex items-center justify-center shadow-lg">NUEVO</div>`,
                        iconSize: [36, 36],
                        iconAnchor: [18, 18]
                    });

                    L.marker([this.selectedLat, this.selectedLng], { icon: newIcon })
                     .addTo(this.map)
                     .bindPopup(`<b>Reporte registrado con éxito</b><br>${this.coordenadasTexto}`)
                     .openPopup();
                     
                } else {
                    alert('Error del servidor: ' + (result.message || 'No se pudo guardar el reporte.'));
                }
            } catch (error) {
                console.error("Error enviando el reporte", error);
                alert("Ocurrió un error de conexión al enviar el reporte.");
            } finally {
                this.cargando = false;
            }
        }
    }
}
</script>
@endsection