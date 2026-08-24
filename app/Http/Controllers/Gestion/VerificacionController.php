<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Bache;
use App\Models\EstadoBache;
use App\Models\HistorialEstado;
use App\Models\Severidad;
use App\Models\Verificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificacionController extends Controller
{
    /**
     * Lista de baches reportados pendientes de verificación.
     */
    public function index()
    {
        $estadoReportadoId = EstadoBache::where('estado', 'Reportado')->value('id');

        $baches = Bache::where('estado_id', $estadoReportadoId)
            ->with('reportes.evidencias')
            ->latest()
            ->get();

        $severidades = Severidad::all();

        return view('gestion.verificaciones.index', compact('baches', 'severidades'));
    }

    /**
     * Registra la verificación de un bache: confirma su existencia y severidad,
     * o lo rechaza si el reporte no corresponde a un bache real.
     */
    public function store(Request $request, Bache $bache)
    {
        $validated = $request->validate([
            'existencia' => ['required', 'boolean'],
            'severidad_id' => ['required_if:existencia,1', 'nullable', 'exists:severidades,id'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ]);

        $existencia = $request->boolean('existencia');

        $nuevoEstado = EstadoBache::where('estado', $existencia ? 'Verificado' : 'Rechazado')->firstOrFail();

        DB::transaction(function () use ($bache, $existencia, $validated, $nuevoEstado) {
            Verificacion::create([
                'bache_id' => $bache->id,
                'user_id' => auth()->id(),
                'severidad_id' => $existencia ? $validated['severidad_id'] : null,
                'existencia' => $existencia,
                'observacion' => $validated['observacion'] ?? null,
                'fecha' => now(),
            ]);

            $bache->estado_id = $nuevoEstado->id;
            $bache->save();

            HistorialEstado::create([
                'bache_id' => $bache->id,
                'estado_id' => $nuevoEstado->id,
                'user_id' => auth()->id(),
                'fecha' => now(),
            ]);
        });

        return redirect()
            ->route('gestion.verificaciones.index')
            ->with('success', $existencia
                ? 'Bache verificado correctamente.'
                : 'Reporte rechazado correctamente.');
    }
}
