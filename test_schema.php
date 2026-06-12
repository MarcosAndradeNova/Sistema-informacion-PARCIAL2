<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_name NOT IN ('migrations', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens', 'users')");

$schema = [];
foreach ($tables as $t) {
    $tableName = $t->table_name;
    $columns = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = ?", [$tableName]);
    $schema[$tableName] = array_map(function($c) { return $c->column_name . ' (' . $c->data_type . ')'; }, $columns);
}

file_put_contents('schema_dump.json', json_encode($schema, JSON_PRETTY_PRINT));
echo "Schema dumped to schema_dump.json\n";
