<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Eliminar asignaciones actuales para evitar conflictos
DB::table('grupodocente')->truncate();

if (!Schema::hasColumn('docente', 'idmateria')) {
    Schema::table('docente', function (Blueprint $table) {
        $table->unsignedBigInteger('idmateria')->nullable();
        $table->foreign('idmateria')->references('id')->on('materia')->onDelete('set null');
    });
    echo "Columna idmateria agregada a la tabla docente.\n";
} else {
    echo "La columna idmateria ya existe.\n";
}

// Asignar una materia distinta a cada uno de los 4 docentes existentes
$docentes = DB::table('docente')->where('estado', 'APROBADO')->get();
$materias = DB::table('materia')->pluck('id')->toArray();

foreach ($docentes as $index => $doc) {
    if (isset($materias[$index])) {
        DB::table('docente')->where('ciusuario', $doc->ciusuario)->update(['idmateria' => $materias[$index]]);
        echo "Docente " . $doc->ciusuario . " actualizado con materia " . $materias[$index] . ".\n";
    }
}
