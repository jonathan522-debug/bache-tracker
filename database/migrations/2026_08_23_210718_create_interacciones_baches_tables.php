<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bache_id')->constrained('baches')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });

        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            // Corrección: Borrado en cascada para evitar huérfanos
            $table->foreignId('reporte_id')->constrained('reportes')->onDelete('cascade'); 
            $table->string('ruta_imagen');
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });

        Schema::create('verificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bache_id')->constrained('baches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('severidad_id')->constrained('severidades');
            $table->boolean('existencia')->default(true);
            $table->text('observacion')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });

        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bache_id')->constrained('baches')->onDelete('cascade');
            $table->foreignId('estado_id')->constrained('estado_baches');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidencias');
        Schema::dropIfExists('reportes');
        Schema::dropIfExists('verificaciones');
        Schema::dropIfExists('historial_estados');
    }
};