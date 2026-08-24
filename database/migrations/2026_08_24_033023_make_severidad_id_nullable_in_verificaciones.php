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
        Schema::table('verificaciones', function (Blueprint $table) {
            $table->dropForeign(['severidad_id']);
            $table->dropColumn('severidad_id');
        });

        Schema::table('verificaciones', function (Blueprint $table) {
            $table->foreignId('severidad_id')->nullable()->after('user_id')->constrained('severidades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verificaciones', function (Blueprint $table) {
            $table->dropForeign(['severidad_id']);
            $table->dropColumn('severidad_id');
        });

        Schema::table('verificaciones', function (Blueprint $table) {
            $table->foreignId('severidad_id')->after('user_id')->constrained('severidades');
        });
    }
};
