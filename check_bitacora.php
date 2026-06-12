<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cols = Illuminate\Support\Facades\DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'bitacora'");
if (empty($cols)) {
    echo "La tabla bitacora no existe.\n";
} else {
    foreach($cols as $c) echo "- " . $c->column_name . " (" . $c->data_type . ")\n";
}
