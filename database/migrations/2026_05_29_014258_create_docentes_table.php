<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            $table->string('ci_usuario', 20)->primary(); // Hereda de usuario
            $table->string('profesion', 100);
            $table->boolean('tiene_maestria')->default(false);
            $table->boolean('tiene_diplomado_ed_superior')->default(false);
            $table->string('estado_contrato', 50)->default('EVALUACION'); // Puede ser CONTRATADO o RECHAZADO
            $table->timestamps();

            // Foreign Key
            $table->foreign('ci_usuario')->references('ci')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
