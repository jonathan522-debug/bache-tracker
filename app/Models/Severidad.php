<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Severidad extends Model
{
    protected $table = 'severidades';
    
    protected $fillable = [
        'nombre',
        'nivel'
    ];

    // Una severidad puede ser aplicada a múltiples verificaciones
    public function verificaciones()
    {
        return $this->hasMany(Verificacion::class, 'severidad_id');
    }
}
