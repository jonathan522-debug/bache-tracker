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
        $validated = $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'nullable|string|max:500',
            'referencia' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $lat = $validated['latitud'];
        $lng = $validated['longitud'];
        
        // REQUISITO: Tolerancia exacta de 8 metros para evitar duplicados
        $toleranciaMetros = 8; 

        // Búsqueda espacial mediante Haversine en SQL
        $bacheCercano = Bache::select('id')
            ->selectRaw("( 6371000 * acos( cos( radians(?) ) * cos( radians( latitud ) ) * cos( radians( longitud ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitud ) ) ) ) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<', $toleranciaMetros)
            ->orderBy('distance')
            ->first();

        DB::beginTransaction();

        try {
            if ($bacheCercano) {
                $bacheId = $bacheCercano->id;
            } else {
                $nuevoBache = Bache::create([
                    'estado_id' => 1, // Pendiente
                    'latitud' => $lat,
                    'longitud' => $lng,
                    'referencia' => $validated['referencia'],
                ]);
                $bacheId = $nuevoBache->id;
            }

            $reporte = Reporte::create([
                'user_id' => auth()->id(),
                'bache_id' => $bacheId,
                'descripcion' => $validated['descripcion'] ?? 'Reporte ciudadano',
                'fecha' => now(),
            ]);

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