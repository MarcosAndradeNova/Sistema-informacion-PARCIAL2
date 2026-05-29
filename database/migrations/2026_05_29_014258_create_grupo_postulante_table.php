<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_postulante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->string('ci_postulante', 20);
            $table->timestamps();

            // Foreign key
            $table->foreign('ci_postulante')->references('ci_usuario')->on('postulantes')->onDelete('cascade');
            
            // Un postulante solo puede estar en un grupo (o un grupo específico)
            $table->unique(['grupo_id', 'ci_postulante']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_postulante');
    }
};
