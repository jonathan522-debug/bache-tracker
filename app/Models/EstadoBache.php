<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoBache extends Model
{
    protected $table = 'estado_baches';
    
    protected $fillable = [
        'estado',
        'descripcion'
    ];

    // Un estado puede estar asignado a muchos baches actualmente
    public function baches()
    {
        return $this->hasMany(Bache::class, 'estado_id');
    }

    // Un estado puede aparecer muchas veces en el historial de diferentes baches
    public function historiales()
    {
        return $this->hasMany(HistorialEstado::class, 'estado_id');
    }
}
