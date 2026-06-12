<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['materia', 'grupodocente', 'grupo'];
foreach ($tables as $t) {
    $cols = Illuminate\Support\Facades\DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = ?", [$t]);
    echo "TABLE: $t\n";
    foreach($cols as $c) echo "- " . $c->column_name . "\n";
}
