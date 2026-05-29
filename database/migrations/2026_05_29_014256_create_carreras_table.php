<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->integer('cupos_gestion')->default(0); // Cupo de alumnos admitidos por gestión
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carreras');
    }
};
