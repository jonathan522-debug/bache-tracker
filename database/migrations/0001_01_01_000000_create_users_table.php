<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('rol');
            $table->timestamps();
        });

        Schema::create('generos', function (Blueprint $table) {
            $table->id();
            $table->string('genero');
            $table->timestamps();
        });

        // 2. MODIFICAR LA TABLA USERS POR DEFECTO
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_id')->constrained('roles');
            $table->foreignId('genero_id')->nullable()->constrained('generos');
            
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            
            // Campos personalizados opcionales
            $table->string('apellidos')->nullable();
            $table->string('telefono')->nullable();
            $table->string('cedula_identidad')->nullable()->unique();
            $table->integer('puntos')->default(0);
            $table->boolean('activo')->default(true);
            $table->string('google_id')->nullable()->unique();
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('generos');
        Schema::dropIfExists('roles');
    }
};
