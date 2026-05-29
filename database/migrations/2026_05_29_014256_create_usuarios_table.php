<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->string('ci', 20)->primary(); // CI como llave primaria y no permite duplicados
            $table->string('nombres', 50);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento');
            $table->char('sexo', 1);
            $table->string('direccion', 150);
            $table->string('telefono', 20)->nullable();
            $table->string('correo_electronico', 100)->unique(); // Validación de correo único
            $table->string('password'); // Agregado para inicio de sesión seguro
            $table->string('rol', 20)->default('POSTULANTE'); // Administrador, Docente, Coordinador, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
