<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePlanAccion extends Model
{
    protected $table = 'detalle_planes_accion';
    
    protected $fillable = [
        'plan_id',
        'bache_id',
        'prioridad',
        'fecha_estimada',
        'observacion'
    ];

    protected $casts = [
        'fecha_estimada' => 'datetime',
    ];

    // RELACIONES

    public function plan()
    {
        return $this->belongsTo(PlanAccion::class, 'plan_id');
    }

    public function bache()
    {
        return $this->belongsTo(Bache::class, 'bache_id');
    }

    // Un detalle puede ser asignado a una o más cuadrillas
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'detalle_plan_id');
    }
}
