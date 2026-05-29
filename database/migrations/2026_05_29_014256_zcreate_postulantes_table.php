<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postulantes', function (Blueprint $table) {
            // CI hereda de usuarios
            $table->string('ci_usuario', 20)->primary();
            
            // Relaciones
            $table->foreignId('carrera_primera_opcion')->constrained('carreras')->onDelete('restrict');
            $table->foreignId('carrera_segunda_opcion')->nullable()->constrained('carreras')->onDelete('set null');
            
            // Datos específicos
            $table->string('colegio_procedencia', 100);
            $table->string('ciudad', 50);
            $table->boolean('titulo_bachiller')->default(false);
            $table->text('otros_requisitos')->nullable();
            
            // Estado de pago
            $table->boolean('pago_efectuado')->default(false);
            $table->timestamps();

            // Foreign Key
            $table->foreign('ci_usuario')->references('ci')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postulantes');
    }
};
