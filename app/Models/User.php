<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'rol_id',
        'genero_id',
        'nombre',
        'apellidos',
        'correo',
        'password',
        'google_id', // Habilitado para registro con Google
        'telefono',
        'cedula_identidad',
        'puntos',
        'activo',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    // RELACIONES

    // Un usuario pertenece a un rol específico
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    // Un usuario puede (o no) tener un género asignado
    public function genero()
    {
        return $this->belongsTo(Genero::class);
    }
}
