<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuadrilla extends Model
{
    protected $table = 'cuadrillas';
    
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    // Una cuadrilla puede tener muchas asignaciones a lo largo del tiempo
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'cuadrilla_id');
    }
}
