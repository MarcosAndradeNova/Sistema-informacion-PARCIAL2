<?php
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Postulacion;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

$email = 'andradenovamarcosdavid@gmail.com';
$ci = '1234567';

// Limpiar antes
$u = Usuario::where('email', $email)->first();
if ($u) {
    Postulante::where('ciusuario', $u->ci)->delete();
    Postulacion::where('ciusuario', $u->ci)->delete();
    Pago::where('ciusuario', $u->ci)->delete();
    $u->delete();
}
User::where('email', $email)->delete();

// Crear
User::create([
    'name' => 'Marcos Test',
    'email' => $email,
    'password' => Hash::make($ci),
    'role' => 'user'
]);

$usuario = Usuario::create([
    'ci' => $ci,
    'nombre' => 'Marcos',
    'apellidopat' => 'Test',
    'fechanac' => '2000-01-01',
    'sexo' => 'M',
    'nacionalidad' => 'Boliviana',
    'direccion' => 'Av Test',
    'telefono' => '12345',
    'email' => $email,
    'tipo' => 'P'
]);

$postulante = Postulante::create([
    'ciusuario' => $ci,
    'estadodocum' => 'APROBADO'
]);

$postulacion = Postulacion::create([
    'codpost' => 999,
    'ciusuario' => $ci,
    'fecha' => now()->toDateString(),
    'hora' => now()->toTimeString(),
    'idadmision' => 1,
    'idsemestre' => 1,
    'codrol' => 4,
]);

echo "Usuario test creado.\n";

// Run verificarPago logic directly
DB::transaction(function () use ($postulante, $usuario, $ci) {
    $pago = \App\Models\Pago::where('ciusuario', $ci)->first();
    if (!$pago) {
        $nuevoId = \App\Models\Pago::max('id') ?? 0;
        $nuevoId++;

        $pago = \App\Models\Pago::create([
            'id' => $nuevoId,
            'numerorecibo' => 'REC-SIM-' . time() . '-' . rand(100, 999),
            'monto' => 350.00,
            'metodopago' => 'Simulado (Bypass)',
            'estado' => 'Pagado',
            'fecha' => now()->toDateString(),
            'ciusuario' => $ci
        ]);
        echo "Pago creado ID: " . $pago->id . "\n";
    }

    $postulacion = Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
    if ($postulacion) {
        if ($pago) {
            $postulacion->idpago = $pago->id;
        }
        $postulacion->save();
    }
    $postulante->estadodocum = 'INSCRITO';
    $postulante->save();
});

echo "Transaction finalizada.\n";
echo "Pagos en DB: " . Pago::count() . "\n";
