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
        Schema::table('postulacion', function (Blueprint $table) {
            $table->decimal('promedio', 5, 2)->nullable();
            $table->string('estado_admision')->nullable(); // APROBADO, REPROBADO, ADMITIDO
            $table->string('carrera_admitida')->nullable(); // Código de la carrera en la que fue admitido
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postulacion', function (Blueprint $table) {
            $table->dropColumn(['promedio', 'estado_admision', 'carrera_admitida']);
        });
    }
};
