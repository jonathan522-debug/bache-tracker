@extends('layouts.app')

@section('content')
<div x-data="bacheApp()" x-init="initMap()" class="relative h-screen w-screen overflow-hidden bg-slate-100 font-sans">

    <header class="absolute top-4 left-4 right-4 z-20 flex flex-col md:flex-row items-center justify-between gap-3 pointer-events-none">
        <div class="bg-white/90 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-lg border border-emerald-100 flex items-center justify-between w-full md:w-auto pointer-events-auto">
            <div class="flex items-center gap-2">
                <div class="bg-emerald-600 text-white p-2 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h1 class="font-bold text-slate-800 leading-none">Baches SCZ</h1>
                    <span class="text-xs font-semibold text-emerald-600">Santa Cruz • Haz clic en el mapa</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-900/80 backdrop-blur-md text-white px-4 py-2 rounded-xl shadow-lg text-xs font-medium pointer-events-auto hidden md:flex items-center gap-2 border border-slate-700">
            <span>🖱️</span>
            <span>Haz clic en el mapa para marcar la ubicación del bache</span>
        </div>
    </header>

    <div id="map" class="h-full w-full z-10 cursor-crosshair"></div>

    <div x-show="!drawerOpen" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20">
        <button @click="abrirFlujoSinPunto()" 
                class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold px-7 py-4 rounded-full shadow-2xl flex items-center gap-3 transition-all duration-200 group border-2 border-emerald-400/30">
            <span class="bg-emerald-500/80 p-1.5 rounded-full group-hover:rotate-90 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </span>
            <span class="text-base tracking-wide">Reportar bache aquí</span>
        </button>
    </div>

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

            <div x-show="paso === 1" class="space-y-5">
                <p class="text-sm text-slate-600">Adjunta una fotografía para verificar la dimensión del bache.</p>
                
                <div @click="simularCargaFoto()" 
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
                            <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover" alt="Bache de prueba">
                            <div class="absolute top-2 right-2 bg-emerald-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow">
                                Foto Lista ✓
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="paso === 2" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nombre Completo</label>
                    <input type="text" x-model="form.nombre" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Teléfono / WhatsApp</label>
                    <input type="text" x-model="form.telefono" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Referencia adicional (Opcional)</label>
                    <textarea x-model="form.referencia" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 transition" placeholder="Ej: Frente al surtidor, carril derecho..."></textarea>
                </div>
            </div>

        </div>

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
                    <button @click="paso = 1" class="w-1/3 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition">
                        Atrás
                    </button>
                    <button @click="finalizarReporte()" class="w-2/3 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg text-sm transition">
                        Enviar Reporte
                    </button>
                </div>
            </template>
        </div>
    </div>

    <div x-show="modalExito" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         style="display: none;">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full text-center shadow-2xl space-y-4 animate-bounce-short">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                ✓
            </div>
            <h3 class="font-bold text-slate-800 text-lg">¡Reporte Guardado!</h3>
            <p class="text-xs text-slate-500">Se fijó la posición en el mapa correctamente.</p>
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
        tempMarker: null, // Marcador dinámico del clic
        selectedLat: -17.7833,
        selectedLng: -63.1821,
        ubicacionTexto: 'Av. Cristóbal de Mendoza, Santa Cruz',
        coordenadasTexto: '-17.7833, -63.1821',
        drawerOpen: false,
        paso: 1,
        fotoCargada: false,
        modalExito: false,
        form: {
            nombre: 'Juan Pérez',
            telefono: '77012345',
            referencia: 'Cerca del cruce semaforizado'
        },

        initMap() {
            // Inicializar mapa centrado en Santa Cruz de la Sierra
            this.map = L.map('map', { zoomControl: true }).setView([-17.7833, -63.1821], 13);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(this.map);

            // Baches de ejemplo en el mapa
            const puntosPrueba = [
                { lat: -17.7780, lng: -63.1810, count: 12 },
                { lat: -17.7950, lng: -63.1650, count: 5 },
                { lat: -17.7650, lng: -63.1950, count: 18 }
            ];

            puntosPrueba.forEach(p => {
                const icon = L.divIcon({
                    className: 'custom-pin',
                    html: `<div class="bg-rose-500 w-8 h-8 rounded-full border-2 border-white text-white font-bold text-xs flex items-center justify-center shadow-md">${p.count}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });
                L.marker([p.lat, p.lng], { icon: icon }).addTo(this.map);
            });

            // 📍 EVENTO DE CLIC EN CUALQUIER PUNTO DEL MAPA
            this.map.on('click', (e) => {
                this.seleccionarUbicacion(e.latlng.lat, e.latlng.lng);
            });
        },

        seleccionarUbicacion(lat, lng) {
            this.selectedLat = lat;
            this.selectedLng = lng;
            this.coordenadasTexto = `GPS: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            this.ubicacionTexto = `Punto seleccionado en el mapa`;

            // Si ya existe un pin temporal previo, lo quitamos
            if (this.tempMarker) {
                this.map.removeLayer(this.tempMarker);
            }

            // Crear icono animado en el punto donde hizo clic
            const pinIcon = L.divIcon({
                className: 'temp-pin',
                html: `<div class="bg-emerald-600 w-10 h-10 rounded-full border-2 border-white text-white font-bold text-lg flex items-center justify-center shadow-2xl animate-bounce">📍</div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            // Colocar el marcador en el mapa
            this.tempMarker = L.marker([lat, lng], { icon: pinIcon }).addTo(this.map);

            // Abrir el panel lateral inmediatamente
            this.drawerOpen = true;
            this.paso = 1;
            this.fotoCargada = false;
        },

        abrirFlujoSinPunto() {
            // Si el usuario hace clic en el botón flotante directo
            this.seleccionarUbicacion(-17.7833, -63.1821);
        },

        cerrarFlujo() {
            this.drawerOpen = false;
            if (this.tempMarker) {
                this.map.removeLayer(this.tempMarker);
            }
        },

        simularCargaFoto() {
            this.fotoCargada = true;
        },

        finalizarReporte() {
            this.drawerOpen = false;
            this.modalExito = true;

            // Fijar el marcador definitivo en el mapa
            if (this.tempMarker) {
                this.map.removeLayer(this.tempMarker);
            }

            const newIcon = L.divIcon({
                className: 'final-pin',
                html: `<div class="bg-emerald-600 w-9 h-9 rounded-full border-2 border-white text-white font-bold text-xs flex items-center justify-center shadow-lg">NUEVO</div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18]
            });

            L.marker([this.selectedLat, this.selectedLng], { icon: newIcon })
             .addTo(this.map)
             .bindPopup(`<b>Reporte registrado</b><br>${this.coordenadasTexto}`)
             .openPopup();
        }
    }
}
</script>
@endsection