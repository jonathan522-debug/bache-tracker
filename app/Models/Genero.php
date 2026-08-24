<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    protected $table = 'generos';
    
    protected $fillable = [
        'genero'
    ];

    // Un género puede estar asociado a muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
