<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    
    protected $fillable = [
        'rol'
    ];

    // Un rol puede pertenecer a muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
