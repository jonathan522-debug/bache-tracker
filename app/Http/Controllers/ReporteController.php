<?php

namespace App\Http\Controllers;

use App\Models\Bache;
use App\Models\Reporte;
use App\Models\Evidencia;
use App\Models\EstadoBache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar la entrada (el ciudadano puede no estar autenticado inicialmente, pero asumimos que tenemos sus datos o id)
        $validated = $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'required|string|max:500',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Máx 10MB
            'calle' => 'nullable|string',
            'referencia' => 'nullable|string',
        ]);

        $lat = $validated['latitud'];
        $lng = $validated['longitud'];
        $toleranciaMetros = 30; // Tolerancia de 30 metros para agrupar reportes

        // 2. Búsqueda Geoespacial Optimizada (Fórmula de Haversine en SQL)
        $bacheCercano = Bache::select('id')
            ->selectRaw("( 6371000 * acos( cos( radians(?) ) * cos( radians( latitud ) ) * cos( radians( longitud ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitud ) ) ) ) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<', $toleranciaMetros)
            ->orderBy('distance')
            ->first();

        DB::beginTransaction();

        try {
            // 3. Crear o asociar el Bache
            if ($bacheCercano) {
                $bacheId = $bacheCercano->id;
            } else {
                // Asumimos que el ID 1 es "Reportado" en tu catálogo de EstadoBache
                $nuevoBache = Bache::create([
                    'estado_id' => 1,
                    'latitud' => $lat,
                    'longitud' => $lng,
                    'calle' => $validated['calle'] ?? null,
                    'referencia' => $validated['referencia'] ?? null,
                ]);
                $bacheId = $nuevoBache->id;
            }

            // 4. Registrar el Reporte
            $reporte = Reporte::create([
                'user_id' => auth()->id() ?? 1, // Ajustar según tu lógica de Auth
                'bache_id' => $bacheId,
                'descripcion' => $validated['descripcion'],
                'fecha' => now(),
            ]);

            // 5. Guardar la Evidencia Fotográfica
            if ($request->hasFile('foto')) {
                $rutaImagen = $request->file('foto')->store('evidencias', 'public');
                Evidencia::create([
                    'reporte_id' => $reporte->id,
                    'ruta_imagen' => $rutaImagen,
                    'fecha' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Reporte registrado correctamente.',
                'bache_id' => $bacheId,
                'asociado_existente' => $bacheCercano ? true : false
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Ocurrió un error interno.'], 500);
        }
    }
}