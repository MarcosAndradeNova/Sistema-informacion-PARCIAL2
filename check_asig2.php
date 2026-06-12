<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$g = Illuminate\Support\Facades\DB::table('grupodocente')->get();
foreach ($g as $r) print_r($r);
