<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('grupodocente', 'whatsapp_link')) {
    Schema::table('grupodocente', function (Blueprint $table) {
        $table->string('whatsapp_link', 255)->nullable();
    });
    echo "Columna whatsapp_link agregada a grupodocente.\n";
} else {
    echo "La columna whatsapp_link ya existe.\n";
}
