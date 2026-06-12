<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Grupos: " . \App\Models\Grupo::count() . "\n";
echo "Docentes aprobados: " . \App\Models\Docente::where('estado', 'APROBADO')->count() . "\n";
echo "Materias: " . \App\Models\Materia::count() . "\n";
echo "Horarios: " . Illuminate\Support\Facades\DB::table('horario')->count() . "\n";
