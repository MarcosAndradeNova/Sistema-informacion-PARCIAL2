<?php
$user = \App\Models\User::where('email', 'andradenovamarcosdavid@gmail.com')->first();
$postulante = \App\Models\Postulante::where('ciusuario', '1234567')->first();
echo json_encode([
    'user' => $user,
    'postulante' => $postulante
]);
