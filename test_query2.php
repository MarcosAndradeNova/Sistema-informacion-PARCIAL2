<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);
$ciDocente = \App\Models\Usuario::where('tipo', 'D')->first()->ci;
$gruposDocente = \App\Models\GrupoDocente::where('ciusuario', $ciDocente)
    ->join('grupo', 'grupodocente.codigogrupo', '=', 'grupo.codigo')
    ->join('horario', 'grupodocente.idhorario', '=', 'horario.id')
    ->select('grupodocente.*', 'grupo.nombre as nombre_grupo', 'horario.dia', 'horario.iniciohorario', 'horario.finhorario', 'horario.nroaula')
    ->get();
echo 'Time: ' . (microtime(true) - $start) . "\n";
echo "Count: " . count($gruposDocente) . "\n";
