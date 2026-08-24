<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model
{
    protected $table = 'historial_estados';
    
    protected $fillable = [
        'bache_id',
        'estado_id',
        'user_id',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // RELACIONES

    public function bache()
    {
        return $this->belongsTo(Bache::class, 'bache_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoBache::class, 'estado_id');
    }

    // El usuario (sistema o funcionario) que provocó el cambio de estado
    public function responsable()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
