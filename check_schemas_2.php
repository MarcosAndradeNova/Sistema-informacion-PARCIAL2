<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['ofrece', 'admision', 'semestre', 'inscribe'];
foreach ($tables as $t) {
    echo "TABLE $t:\n";
    $cols = Illuminate\Support\Facades\DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = ?", [$t]);
    print_r($cols);
}
