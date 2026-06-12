<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    // Aulas
    $aulas = [
        ['nro' => 'A1', 'capacidad' => 70],
        ['nro' => 'A2', 'capacidad' => 70],
        ['nro' => 'A3', 'capacidad' => 70],
        ['nro' => 'A4', 'capacidad' => 70],
    ];
    
    foreach ($aulas as $a) {
        $exists = DB::table('aula')->where('nro', $a['nro'])->exists();
        if (!$exists) {
            DB::table('aula')->insert($a);
        }
    }

    // Horarios
    $horarios = [
        ['id' => 1, 'dia' => 'Lunes a Viernes', 'iniciohorario' => '08:00:00', 'finhorario' => '10:00:00', 'nroaula' => 'A1'],
        ['id' => 2, 'dia' => 'Lunes a Viernes', 'iniciohorario' => '10:00:00', 'finhorario' => '12:00:00', 'nroaula' => 'A1'],
        ['id' => 3, 'dia' => 'Lunes a Viernes', 'iniciohorario' => '14:00:00', 'finhorario' => '16:00:00', 'nroaula' => 'A2'],
        ['id' => 4, 'dia' => 'Lunes a Viernes', 'iniciohorario' => '16:00:00', 'finhorario' => '18:00:00', 'nroaula' => 'A2'],
        ['id' => 5, 'dia' => 'Lunes a Viernes', 'iniciohorario' => '08:00:00', 'finhorario' => '10:00:00', 'nroaula' => 'A3'],
        ['id' => 6, 'dia' => 'Lunes a Viernes', 'iniciohorario' => '10:00:00', 'finhorario' => '12:00:00', 'nroaula' => 'A3'],
    ];

    foreach ($horarios as $h) {
        $exists = DB::table('horario')->where('id', $h['id'])->exists();
        if (!$exists) {
            DB::table('horario')->insert($h);
        }
    }

    DB::commit();
    echo "Aulas y Horarios sembrados correctamente.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
