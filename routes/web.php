<?php

use App\Http\Controllers\BacheController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Gestion\VerificacionController;


Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de Autenticación Tradicional y Social
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // O tu vista de login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de Google Socialite
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::middleware(['auth'])->group(function () {
    Route::get('/baches', [BacheController::class, 'index'])->name('baches.index');
    Route::post('/baches/reportar', [BacheController::class, 'store'])->name('baches.store');
    Route::get('/mi-perfil', [UserController::class, 'perfil'])->name('perfil.index');
    Route::get('/mis-reportes', [BacheController::class, 'misReportes'])->name('reportes.personales');
});

// Panel de Gestión Municipal (Funcionarios / Administradores)
Route::prefix('gestion')->name('gestion.')->middleware(['auth', 'role:Funcionario,Administrador'])->group(function () {
    Route::get('/verificaciones', [VerificacionController::class, 'index'])->name('verificaciones.index');
    Route::post('/verificaciones/{bache}', [VerificacionController::class, 'store'])->name('verificaciones.store');
});

// Panel Administrativo de Usuarios
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Administrador'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create'); // Nueva vista
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::patch('/usuarios/{id}/rol', [UserController::class, 'updateRole'])->name('usuarios.updateRole'); // Modificar rol
    Route::patch('/usuarios/{id}/toggle', [UserController::class, 'toggleStatus'])->name('usuarios.toggle');
});