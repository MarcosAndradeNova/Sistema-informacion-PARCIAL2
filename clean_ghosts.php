<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    // Buscar los usuarios falsos que tengan CI '0000000', '0000001', '0000002', '0000003', '0000004'
    $cis = ['0000000', '0000001', '0000002', '0000003', '0000004'];
    
    // Eliminar de tabla docente y usuario
    DB::table('grupodocente')->whereIn('ciusuario', $cis)->delete();
    DB::table('docente')->whereIn('ciusuario', $cis)->delete();
    DB::table('usuario')->whereIn('ci', $cis)->delete();

    DB::commit();
    echo "Fantasmas eliminados con éxito.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
