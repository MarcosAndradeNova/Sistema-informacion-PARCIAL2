<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$ad = DB::select('SELECT * FROM admision');
print_r($ad);

$post = DB::select('SELECT * FROM postulacion LIMIT 5');
print_r($post);
