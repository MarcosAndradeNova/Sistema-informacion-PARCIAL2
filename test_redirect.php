<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/registro-docente', 'POST', [
    'name' => 'Prof. X', 
    'email' => 'profx'.time().'@test.com', 
    'password' => 'password123', 
    'password_confirmation' => 'password123', 
    '_token' => 'dummy'
]);
// Skip CSRF by modifying the middleware or just mocking it
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Location: " . $response->headers->get('Location') . "\n";
