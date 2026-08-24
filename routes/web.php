<?php

use App\Http\Controllers\BacheController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;


// Rutas de Autenticación Tradicional y Social
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // O tu vista de login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de Google Socialite
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/baches', function () {
    return view('baches.index');
})->middleware('auth'); // Protegido para que solo entren usuarios logueados


// Panel Administrativo de Usuarios
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create'); // Nueva vista
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::patch('/usuarios/{id}/rol', [UserController::class, 'updateRole'])->name('usuarios.updateRole'); // Modificar rol
    Route::patch('/usuarios/{id}/toggle', [UserController::class, 'toggleStatus'])->name('usuarios.toggle');
});