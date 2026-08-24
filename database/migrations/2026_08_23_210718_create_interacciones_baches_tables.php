<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('bache_id')->constrained('baches');
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha');
            $table->timestamps();
        });

        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('reportes');
            $table->string('ruta_imagen');
            $table->timestamp('fecha');
            $table->timestamps();
        });

        Schema::create('verificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bache_id')->constrained('baches');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('severidad_id')->constrained('severidades');
            $table->boolean('existencia');
            $table->text('observacion')->nullable();
            $table->timestamp('fecha');
            $table->timestamps();
        });

        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bache_id')->constrained('baches');
            $table->foreignId('estado_id')->constrained('estado_baches');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
        Schema::dropIfExists('evidencias');
        Schema::dropIfExists('verificaciones');
        Schema::dropIfExists('historial_estados');
    }
};
