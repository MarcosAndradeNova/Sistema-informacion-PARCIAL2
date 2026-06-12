<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Illuminate\Support\Facades\DB::select("SELECT kcu.column_name 
FROM information_schema.table_constraints tco
JOIN information_schema.key_column_usage kcu 
          ON kcu.constraint_name = tco.constraint_name
          AND kcu.constraint_schema = tco.constraint_schema
          AND kcu.constraint_name = tco.constraint_name
WHERE tco.constraint_type = 'PRIMARY KEY' AND kcu.table_name = 'grupodocente'");
print_r($columns);
