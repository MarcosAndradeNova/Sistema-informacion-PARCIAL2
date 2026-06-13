<?php

$nuevoId = \App\Models\Pago::max('id') ?? 0;
$nuevoId++;

try {
    $pagoId = \App\Models\Pago::insertGetId([
        'id' => $nuevoId,
        'numerorecibo' => 'REC-SIM-' . time() . '-' . rand(100, 999),
        'monto' => 350.00,
        'metodopago' => 'Simulado (Bypass)',
        'estado' => 'Pagado',
        'fecha' => now()->toDateString(),
        'ciusuario' => 'dummy' // foreign key constraint?
    ]);
    echo "Inserted ID: $pagoId\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
