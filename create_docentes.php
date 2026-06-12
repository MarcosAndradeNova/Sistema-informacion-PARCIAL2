<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Usuario;
use App\Models\Docente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

$docentesData = [
    [
        'ci' => '8000001',
        'nombre' => 'Carlos',
        'apellidopat' => 'Mendoza',
        'email' => 'carlos.mendoza@universidad.edu',
        'profesion' => 'Ingeniero Informático',
    ],
    [
        'ci' => '8000002',
        'nombre' => 'Ana',
        'apellidopat' => 'Suarez',
        'email' => 'ana.suarez@universidad.edu',
        'profesion' => 'Licenciada en Matemáticas',
    ],
    [
        'ci' => '8000003',
        'nombre' => 'Fernando',
        'apellidopat' => 'Villarroel',
        'email' => 'fernando.villarroel@universidad.edu',
        'profesion' => 'Físico Cuántico',
    ]
];

DB::beginTransaction();
try {
    foreach ($docentesData as $data) {
        $user = Usuario::firstOrCreate(
            ['ci' => $data['ci']],
            [
                'nombre' => $data['nombre'],
                'apellidopat' => $data['apellidopat'],
                'apellidomat' => '',
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'tipo' => 'D',
            ]
        );

        Docente::firstOrCreate(
            ['ciusuario' => $user->ci],
            [
                'coddocente' => (int) $user->ci,
                'profesion' => $data['profesion'],
                'nivelformacion' => 'Maestría',
                'experiencia' => rand(3, 15),
                'codrol' => 2,
                'estado' => 'APROBADO',
            ]
        );
    }
    DB::commit();
    echo "3 docentes creados con éxito.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
