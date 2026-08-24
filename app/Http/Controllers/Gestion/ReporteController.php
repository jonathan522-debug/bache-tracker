<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Reporte;

class ReporteController extends Controller
{
    /**
     * Listado de todos los reportes ciudadanos, para el equipo municipal.
     */
    public function index()
    {
        $reportes = Reporte::with(['bache.estado', 'evidencias', 'ciudadano'])
            ->latest('fecha')
            ->get();

        return view('gestion.reportes.index', compact('reportes'));
    }
}
