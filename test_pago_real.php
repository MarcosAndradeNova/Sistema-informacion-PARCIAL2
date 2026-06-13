<?php

$ci = \App\Models\Usuario::first()->ci;
$nuevoId = \App\Models\Pago::max('id') ?? 0;
$nuevoId++;

try {
    $pago = \App\Models\Pago::create([
        'id' => $nuevoId,
        'numerorecibo' => 'REC-SIM-' . time() . '-' . rand(100, 999),
        'monto' => 350.00,
        'metodopago' => 'Simulado (Bypass)',
        'estado' => 'Pagado',
        'fecha' => now()->toDateString(),
        'ciusuario' => $ci
    ]);
    echo "Inserted ID: " . $pago->id . "\n";
    
    // cleanup
    $pago->delete();
    echo "Deleted dummy pago.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
