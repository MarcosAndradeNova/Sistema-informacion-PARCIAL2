$m = \App\Models\Materia::first(); 
if($m) {
    dump($m->nombre); 
    $u = \App\Models\Usuario::where('email', 'million10000005years@gmail.com')->first(); 
    if ($u) {
        $u->estado_aprobacion = 'APROBADO'; 
        $u->save(); 
        $m->docente_ci = $u->ci; 
        $m->save(); 
        dump('Docente aprobado y asignado a '.$m->nombre);
    }
}
