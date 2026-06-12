<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $postulante = \App\Models\Postulante::first();
    \App\Models\ResultadoExam::updateOrCreate(
        ['nroexamen' => 1, 'ciusuario' => $postulante->ciusuario, 'idmateria' => 1],
        ['codigogrupo' => 1, 'codpost' => 1, 'calificacion' => 100]
    );
    
    // Now try updating
    \App\Models\ResultadoExam::updateOrCreate(
        ['nroexamen' => 1, 'ciusuario' => $postulante->ciusuario, 'idmateria' => 1],
        ['codigogrupo' => 1, 'codpost' => 1, 'calificacion' => 85]
    );
    echo "Success\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
