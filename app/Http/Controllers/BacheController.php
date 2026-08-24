<?php

namespace App\Http\Controllers;

use App\Models\Bache;
use Illuminate\Http\Request;

class BacheController extends Controller
{
    public function getPuntosMapa()
    {
        // Traemos los baches con la cantidad de reportes usando withCount para ser extremadamente rápidos
        $baches = Bache::with('estado:id,estado')
            ->withCount('reportes')
            ->get(['id', 'latitud', 'longitud', 'estado_id']);

        return response()->json($baches);
    }
}