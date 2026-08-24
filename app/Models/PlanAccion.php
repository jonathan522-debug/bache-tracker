<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAccion extends Model
{
    protected $table = 'planes_accion';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_creacion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'user_id'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    // RELACIONES

    // El funcionario que creó o administra el plan
    public function funcionario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Un plan agrupa múltiples baches a reparar
    public function detalles()
    {
        return $this->hasMany(DetallePlanAccion::class, 'plan_id');
    }
}
