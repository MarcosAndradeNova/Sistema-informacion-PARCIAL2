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
        Schema::table('postulante', function (Blueprint $table) {
            $table->enum('estado_admision', [
                'REGISTRADO', 
                'DOCUMENTOS_PENDIENTES', 
                'DOCUMENTOS_VERIFICADOS', 
                'DOCUMENTOS_RECHAZADOS', 
                'PAGO_PENDIENTE', 
                'PAGO_CONFIRMADO', 
                'POSTULANTE_ACTIVO'
            ])->default('DOCUMENTOS_PENDIENTES');
            $table->text('observaciones_documentos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postulante', function (Blueprint $table) {
            $table->dropColumn(['estado_admision', 'observaciones_documentos']);
        });
    }
};
