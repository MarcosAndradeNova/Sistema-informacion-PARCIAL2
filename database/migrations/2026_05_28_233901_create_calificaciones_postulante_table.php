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
        Schema::create('calificaciones_postulante', function (Blueprint $table) {
            $table->id();
            $table->string('ci_usuario', 20);
            $table->string('materia', 50); // Computación, Matemáticas, Inglés, Física
            $table->integer('nota1')->default(0);
            $table->integer('nota2')->default(0);
            $table->integer('nota3')->default(0);
            $table->float('promedio')->default(0);
            $table->string('estado', 20)->default('REPROBADO');
            $table->timestamps();

            // Foreign key to postulante table
            $table->foreign('ci_usuario')->references('ci_usuario')->on('postulante')->onDelete('cascade');
            
            // Un postulante solo puede tener un registro de calificaciones por materia
            $table->unique(['ci_usuario', 'materia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones_postulante');
    }
};
