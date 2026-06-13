<?php

$email = 'andradenovamarcosdavid@gmail.com';
$user = \App\Models\User::where('email', $email)->first();
$usuario = \App\Models\Usuario::where('email', $email)->first();

if ($usuario) {
    $ci = $usuario->ci;
    $postulante = \App\Models\Postulante::where('ciusuario', $ci)->first();
    if ($postulante) {
        $postulaciones = \App\Models\Postulacion::where('ciusuario', $ci)->get();
        foreach ($postulaciones as $post) {
            \App\Models\Inscribe::where('codpost', $post->codpost)->delete();
            \App\Models\ResultadoExam::where('codpost', $post->codpost)->delete();
            $post->delete();
        }
        $postulante->delete();
    }
    
    // delete other potential relations
    \App\Models\Pago::where('ciusuario', $ci)->delete();
    
    $usuario->delete();
    echo "Usuario eliminado.\n";
}

if ($user) {
    $user->delete();
    echo "User login eliminado.\n";
}

echo "Proceso completado.\n";
