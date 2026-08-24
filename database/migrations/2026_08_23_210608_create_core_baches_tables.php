<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('estado_baches', function (Blueprint $table) {
        $table->id();
        $table->string('estado');
        $table->string('descripcion')->nullable();
        $table->timestamps();
    });

    Schema::create('severidades', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->integer('nivel');
        $table->timestamps();
    });

    Schema::create('baches', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estado_id')->constrained('estado_baches');
        $table->string('titulo')->nullable();
        $table->text('descripcion')->nullable();
        $table->decimal('latitud', 10, 8);
        $table->decimal('longitud', 11, 8);
        $table->string('calle')->nullable();
        $table->text('referencia')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_baches');
        Schema::dropIfExists('severidades');
        Schema::dropIfExists('baches');
    }
};
