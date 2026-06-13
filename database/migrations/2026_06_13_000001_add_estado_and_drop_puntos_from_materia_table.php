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
        Schema::table('materia', function (Blueprint $table) {
            if (!Schema::hasColumn('materia', 'estado')) {
                $table->string('estado', 20)->default('HABILITADO')->after('nombre');
            }

            if (Schema::hasColumn('materia', 'puntos')) {
                $table->dropColumn('puntos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materia', function (Blueprint $table) {
            if (Schema::hasColumn('materia', 'estado')) {
                $table->dropColumn('estado');
            }

            if (!Schema::hasColumn('materia', 'puntos')) {
                $table->integer('puntos')->default(25);
            }
        });
    }
};