<?php

namespace App\Http\Controllers;

use App\Models\Bache;
use App\Models\Reporte;
use App\Models\Evidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BacheController extends Controller
{
    public function index()
    {
        $baches = Bache::select('id', 'latitud', 'longitud', 'referencia', 'estado_id')->get();
        return view('baches.index', compact('baches')); 
    }
    public function misReportes()
    {
        // Obtenemos los reportes del usuario activo con su bache y evidencia asociada
        $reportes = Reporte::with(['bache', 'evidencias'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('baches.mis-reportes', compact('reportes'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'referencia' => 'nullable|string',
            'foto' => 'required|image|max:10240', // Máx 10MB
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear el registro del Bache principal
            $bache = Bache::create([
                'titulo' => 'Reporte Ciudadano',
                'descripcion' => $request->referencia ?? 'Bache reportado por usuario',
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'calle' => 'Por determinar', // Esto lo podríamos automatizar luego
                'referencia' => $request->referencia,
                'estado_id' => 1, // Suponiendo que 1 es "Reportado / Nuevo"
            ]);

            // 2. Crear la interacción del Reporte ligado al usuario actual
            $reporte = Reporte::create([
                'user_id' => Auth::id(),
                'bache_id' => $bache->id,
                'descripcion' => 'Reporte generado desde la aplicación web.',
            ]);

            // 3. Subir la imagen y crear la Evidencia
            if ($request->hasFile('foto')) {
                $rutaImagen = $request->file('foto')->store('evidencias', 'public');
                
                Evidencia::create([
                    'reporte_id' => $reporte->id,
                    'ruta_imagen' => $rutaImagen,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'bache' => $bache], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}