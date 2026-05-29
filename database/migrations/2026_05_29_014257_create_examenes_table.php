<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->string('ci_postulante', 20);
            $table->string('materia', 50); // Computación, Matemáticas, Inglés, Física
            
            // Notas limitadas entre 0 y 100 por lógica de controlador, tipo entero
            $table->integer('nota1')->default(0);
            $table->integer('nota2')->default(0);
            $table->integer('nota3')->default(0);
            
            $table->decimal('promedio', 5, 2)->default(0);
            $table->string('estado', 20)->default('REPROBADO'); // APROBADO o REPROBADO
            
            $table->timestamps();

            // Restricciones
            $table->foreign('ci_postulante')->references('ci_usuario')->on('postulantes')->onDelete('cascade');
            $table->unique(['ci_postulante', 'materia']); // Un solo registro de 3 notas por materia por postulante
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};
