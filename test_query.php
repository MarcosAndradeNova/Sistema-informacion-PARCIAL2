<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);
$ciDocente = \App\Models\Usuario::where('tipo', 'D')->first()->ci;
$misGruposCodigos = \App\Models\GrupoDocente::where('ciusuario', $ciDocente)->pluck('codigogrupo');
$grupos = \App\Models\Grupo::whereIn('codigo', $misGruposCodigos)->with(['postulantes' => function($query) {
    $query->where('estadodocum', 'INSCRITO')->with('usuario');
}])->get();
echo 'Time: ' . (microtime(true) - $start) . "\n";
echo "Count: " . count($grupos) . "\n";
