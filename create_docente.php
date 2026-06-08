$u = \App\Models\User::where('email', 'million10000005years@gmail.com')->first();
if (!$u) {
    $u = new \App\Models\User;
    $u->name = 'Docente Prueba';
    $u->email = 'million10000005years@gmail.com';
    $u->password = \Hash::make('password');
    $u->save();
}

$us = \App\Models\Usuario::where('email', 'million10000005years@gmail.com')->first();
if (!$us) {
    $us = new \App\Models\Usuario;
    $us->ci = '999999';
    $us->nombre = 'Docente';
    $us->apellido_pat = 'Prueba';
    $us->apellido_mat = 'Prueba';
    $us->sexo = 'M';
    $us->telefono = '77777777';
    $us->direccion = 'Av. Prueba';
    $us->email = 'million10000005years@gmail.com';
    $us->fechanac = '1990-01-01';
    $us->nacionalidad = 'Boliviana';
    $us->tipo = 'D';
    $us->save();
}
echo "Docente creado exitosamente.\n";
