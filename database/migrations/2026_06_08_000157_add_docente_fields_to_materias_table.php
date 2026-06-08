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
        Schema::table('materias', function (Blueprint $table) {
            $table->string('docente_ci')->nullable();
            $table->text('temario_avance')->nullable();
            $table->text('enlaces_material')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            $table->dropColumn(['docente_ci', 'temario_avance', 'enlaces_material']);
        });
    }
};
