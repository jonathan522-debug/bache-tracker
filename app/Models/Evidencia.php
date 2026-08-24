<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $table = 'evidencias';
    
    protected $fillable = [
        'reporte_id',
        'ruta_imagen',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // La evidencia pertenece a un reporte específico
    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }
}
