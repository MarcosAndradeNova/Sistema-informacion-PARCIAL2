<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$materias = \App\Models\Materia::all();
foreach ($materias as $m) echo $m->id . " - " . $m->nombre . "\n";
