<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verificacion extends Model
{
    protected $table = 'verificaciones';
    
    protected $fillable = [
        'bache_id',
        'user_id',
        'severidad_id',
        'existencia',
        'observacion',
        'fecha'
    ];

    protected $casts = [
        'existencia' => 'boolean',
        'fecha' => 'datetime',
    ];

    // RELACIONES

    public function bache()
    {
        return $this->belongsTo(Bache::class, 'bache_id');
    }

    // El funcionario que realizó la verificación
    public function funcionario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // El nivel de gravedad asignado en esta inspección
    public function severidad()
    {
        return $this->belongsTo(Severidad::class, 'severidad_id');
    }
}
