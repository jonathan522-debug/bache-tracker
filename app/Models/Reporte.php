<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reportes';
    
    protected $fillable = [
        'user_id',
        'bache_id',
        'descripcion',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // RELACIONES

    // El ciudadano que hizo el reporte
    public function ciudadano()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // El bache físico al que está asociado
    public function bache()
    {
        return $this->belongsTo(Bache::class, 'bache_id');
    }

    // Un reporte puede tener varias fotos de evidencia
    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'reporte_id');
    }
}
