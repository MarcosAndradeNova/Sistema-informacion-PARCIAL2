<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->integer('capacidad_maxima')->default(70); // Máximo 70 estudiantes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
