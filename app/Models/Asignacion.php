<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    
    protected $fillable = [
        'detalle_plan_id',
        'cuadrilla_id',
        'fecha_asignacion',
        'fecha_programada',
        'observacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_programada' => 'datetime',
    ];

    // RELACIONES

    public function detallePlan()
    {
        return $this->belongsTo(DetallePlanAccion::class, 'detalle_plan_id');
    }

    public function cuadrilla()
    {
        return $this->belongsTo(Cuadrilla::class, 'cuadrilla_id');
    }
}
