<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    // 1. Limpiar las asignaciones para que no haya conflicto de Foreign Keys
    DB::table('grupodocente')->delete();
    
    // 2. Limpiar horarios antiguos
    DB::table('horario')->delete();

    // 3. Crear los nuevos horarios (1.5 horas, 3 días a la semana)
    $dias = ['Lun-Mie-Vie', 'Mar-Jue-Sab'];
    
    $bloques = [
        ['07:00:00', '08:30:00'],
        ['08:30:00', '10:00:00'],
        ['10:00:00', '11:30:00'],
        ['11:30:00', '13:00:00'],
        ['14:00:00', '15:30:00'],
        ['15:30:00', '17:00:00'],
        ['17:00:00', '18:30:00'],
        ['18:30:00', '20:00:00']
    ];

    $aulas = ['A1', 'A2', 'A3', 'A4'];

    $horariosAInsertar = [];
    $id = 1;

    foreach ($dias as $dia) {
        foreach ($aulas as $aula) {
            foreach ($bloques as $bloque) {
                $horariosAInsertar[] = [
                    'id' => $id++,
                    'dia' => $dia,
                    'iniciohorario' => $bloque[0],
                    'finhorario' => $bloque[1],
                    'nroaula' => $aula
                ];
            }
        }
    }

    DB::table('horario')->insert($horariosAInsertar);

    DB::commit();
    echo "Horarios actualizados exitosamente con duración de 1.5hrs y distribuidos en Lun-Mie-Vie / Mar-Jue-Sab.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
