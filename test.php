<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\Usuario::where('rol', 'A')->first(); // Get admin user
if ($user) {
    Auth::login($user);
} else {
    // Force auth if no admin user found
    $user = App\Models\Usuario::first();
    if ($user) Auth::login($user);
}

$request = Illuminate\Http\Request::create('/admin/grupos', 'GET');
$response = $kernel->handle($request);
echo "Status Grupos: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 500) {
    echo $response->getContent() . "\n";
}

$request2 = Illuminate\Http\Request::create('/admin/reportes', 'GET');
$response2 = $kernel->handle($request2);
echo "Status Reportes: " . $response2->getStatusCode() . "\n";
if ($response2->getStatusCode() == 500) {
    echo substr($response2->getContent(), 0, 1000) . "\n";
}
