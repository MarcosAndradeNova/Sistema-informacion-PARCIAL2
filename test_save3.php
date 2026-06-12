<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$postulante = \App\Models\Postulante::first();
$res = \App\Models\ResultadoExam::where('ciusuario', $postulante->ciusuario)->first();
echo "Calificacion: " . $res->calificacion . "\n";
