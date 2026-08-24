<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Catálogo de Cuadrillas de Trabajo
        Schema::create('cuadrillas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // 2. Planes de Acción (Depende de Funcionario)
        Schema::create('planes_accion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->string('estado'); // Ej: Borrador, En Progreso, Finalizado
            $table->foreignId('user_id')->constrained('users'); // Funcionario a cargo
            $table->timestamps();
        });

        // 3. Detalle del Plan (Relaciona el Plan con los Baches)
        Schema::create('detalle_planes_accion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('planes_accion')->onDelete('cascade');
            $table->foreignId('bache_id')->constrained('baches');
            $table->integer('prioridad')->default(1); 
            $table->timestamp('fecha_estimada')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });

        // 4. Asignaciones (Relaciona el Detalle con las Cuadrillas)
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_plan_id')->constrained('detalle_planes_accion')->onDelete('cascade');
            $table->foreignId('cuadrilla_id')->constrained('cuadrillas');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_programada')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // El orden de eliminación debe ser inverso al de creación
        Schema::dropIfExists('asignaciones');
        Schema::dropIfExists('detalle_planes_accion');
        Schema::dropIfExists('planes_accion');
        Schema::dropIfExists('cuadrillas');
    }
};