<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Bache;
use App\Models\DetallePlanAccion;
use App\Models\EstadoBache;
use App\Models\HistorialEstado;
use App\Models\PlanAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanAccionController extends Controller
{
    public function index()
    {
        $planes = PlanAccion::with('funcionario')->withCount('detalles')->latest()->get();

        return view('gestion.planes.index', compact('planes'));
    }

    public function create()
    {
        return view('gestion.planes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $plan = PlanAccion::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('gestion.planes.show', $plan)->with('success', 'Plan de acción creado correctamente.');
    }

    public function show(PlanAccion $plan)
    {
        $plan->load('detalles.bache.estado', 'funcionario');

        $estadoVerificadoId = EstadoBache::where('estado', 'Verificado')->value('id');
        $bachesDisponibles = Bache::where('estado_id', $estadoVerificadoId)->get();

        return view('gestion.planes.show', compact('plan', 'bachesDisponibles'));
    }

    public function agregarBache(Request $request, PlanAccion $plan)
    {
        $validated = $request->validate([
            'bache_id' => ['required', 'exists:baches,id'],
            'prioridad' => ['required', 'integer', 'min:1'],
            'fecha_estimada' => ['nullable', 'date'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ]);

        $bache = Bache::with('estado')->findOrFail($validated['bache_id']);

        if ($bache->estado?->estado !== 'Verificado') {
            return back()->withErrors(['bache_id' => 'Ese bache ya no está disponible para planificar.']);
        }

        $estadoPlanificacion = EstadoBache::where('estado', 'En Planificación')->firstOrFail();

        DB::transaction(function () use ($plan, $bache, $validated, $estadoPlanificacion) {
            DetallePlanAccion::create([
                'plan_id' => $plan->id,
                'bache_id' => $bache->id,
                'prioridad' => $validated['prioridad'],
                'fecha_estimada' => $validated['fecha_estimada'] ?? null,
                'observacion' => $validated['observacion'] ?? null,
            ]);

            $bache->estado_id = $estadoPlanificacion->id;
            $bache->save();

            HistorialEstado::create([
                'bache_id' => $bache->id,
                'estado_id' => $estadoPlanificacion->id,
                'user_id' => auth()->id(),
                'fecha' => now(),
            ]);
        });

        return redirect()->route('gestion.planes.show', $plan)->with('success', 'Bache agregado al plan correctamente.');
    }

    public function updateEstado(Request $request, PlanAccion $plan)
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:Borrador,En Progreso,Finalizado'],
        ]);

        DB::transaction(function () use ($plan, $validated) {
            $plan->estado = $validated['estado'];
            $plan->save();

            // Al finalizar el plan, todos sus baches pasan a "Reparado" y
            // dejan de mostrarse en el mapa público.
            if ($validated['estado'] === 'Finalizado') {
                $estadoReparado = EstadoBache::where('estado', 'Reparado')->firstOrFail();

                $plan->load('detalles.bache');

                foreach ($plan->detalles as $detalle) {
                    if ($detalle->bache) {
                        $this->marcarBacheReparado($detalle->bache, $estadoReparado);
                    }
                }
            }
        });

        return redirect()->route('gestion.planes.show', $plan)->with('success', 'Estado del plan actualizado correctamente.');
    }

    /**
     * Marca un bache concreto del plan como reparado (deja de aparecer en el mapa).
     */
    public function marcarReparado(PlanAccion $plan, Bache $bache)
    {
        $estadoReparado = EstadoBache::where('estado', 'Reparado')->firstOrFail();

        if ($bache->estado_id === $estadoReparado->id) {
            return redirect()->route('gestion.planes.show', $plan)->with('success', 'El bache ya estaba marcado como reparado.');
        }

        DB::transaction(fn () => $this->marcarBacheReparado($bache, $estadoReparado));

        return redirect()->route('gestion.planes.show', $plan)->with('success', 'Bache marcado como reparado.');
    }

    /**
     * Cambia el estado del bache a "Reparado" y deja constancia en el historial.
     */
    private function marcarBacheReparado(Bache $bache, EstadoBache $estadoReparado): void
    {
        if ($bache->estado_id === $estadoReparado->id) {
            return;
        }

        $bache->estado_id = $estadoReparado->id;
        $bache->save();

        HistorialEstado::create([
            'bache_id' => $bache->id,
            'estado_id' => $estadoReparado->id,
            'user_id' => auth()->id(),
            'fecha' => now(),
        ]);
    }
}
