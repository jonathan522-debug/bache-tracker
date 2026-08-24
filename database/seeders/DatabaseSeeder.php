<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Rol;
use App\Models\Genero;
use App\Models\EstadoBache;
use App\Models\Severidad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles del Sistema
        $rolCiudadano = Rol::create(['rol' => 'Ciudadano']);
        $rolFuncionario = Rol::create(['rol' => 'Funcionario']);
        $rolAdmin = Rol::create(['rol' => 'Administrador']);

        // 2. Géneros (Catálogo básico)
        $generoMasculino = Genero::create(['genero' => 'Masculino']);
        $generoFemenino = Genero::create(['genero' => 'Femenino']);
        $generoOtro = Genero::create(['genero' => 'Otro']);

        // 3. Estados del Bache (Ciclo de vida del reporte)
        EstadoBache::create(['estado' => 'Reportado', 'descripcion' => 'El bache ha sido reportado por un ciudadano pero no verificado.']);
        EstadoBache::create(['estado' => 'Verificado', 'descripcion' => 'Un inspector municipal ha confirmado la existencia del bache.']);
        EstadoBache::create(['estado' => 'En Planificación', 'descripcion' => 'El bache ha sido incluido en un plan de acción operativo.']);
        EstadoBache::create(['estado' => 'En Reparación', 'descripcion' => 'La cuadrilla se encuentra trabajando en el lugar.']);
        EstadoBache::create(['estado' => 'Reparado', 'descripcion' => 'El bache ha sido solucionado exitosamente.']);
        EstadoBache::create(['estado' => 'Rechazado', 'descripcion' => 'Se verificó y el bache no existe o el reporte es inválido.']);

        // 4. Severidades (Niveles de urgencia)
        Severidad::create(['nombre' => 'Leve', 'nivel' => 1]);
        Severidad::create(['nombre' => 'Moderado', 'nivel' => 2]);
        Severidad::create(['nombre' => 'Grave / Peligroso', 'nivel' => 3]);

        // 5. Usuario Administrador Inicial (Inicio de sesión tradicional)
        User::create([
            'rol_id' => $rolAdmin->id,
            'genero_id' => $generoMasculino->id,
            'nombre' => 'Administrador',
            'apellidos' => 'Municipal',
            'correo' => 'admin@reportascz.com',
            'password' => Hash::make('admin123'),
            'telefono' => '70000000',
            'cedula_identidad' => '12345678',
            'puntos' => 0,
            'activo' => true,
        ]);
    }
}
