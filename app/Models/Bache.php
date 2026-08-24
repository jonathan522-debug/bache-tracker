<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bache extends Model
{
    protected $table = 'baches';
    
    protected $fillable = [
        'estado_id',
        'titulo',
        'descripcion',
        'latitud',
        'longitud',
        'calle',
        'referencia'
    ];

    /**
     * Cast de atributos para asegurar tipos de datos correctos.
     */
    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    // RELACIONES

    // Un bache pertenece a un estado actual
    public function estado()
    {
        return $this->belongsTo(EstadoBache::class, 'estado_id');
    }

    // Un bache agrupa múltiples quejas o reportes de ciudadanos
    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'bache_id');
    }

    // Un bache puede tener múltiples verificaciones a lo largo del tiempo
    public function verificaciones()
    {
        return $this->hasMany(Verificacion::class, 'bache_id');
    }

    // Historial de cambios de estado del bache
    public function historialEstados()
    {
        return $this->hasMany(HistorialEstado::class, 'bache_id');
    }
}
