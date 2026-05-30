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
        Schema::table('carrera', function (Blueprint $table) {
            $table->string('semestre', 20)->nullable()->default('1-2026');
            $table->integer('cupo')->default(150);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carrera', function (Blueprint $table) {
            $table->dropColumn(['semestre', 'cupo']);
        });
    }
};
