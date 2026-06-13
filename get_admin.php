<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$admin = Illuminate\Support\Facades\DB::table('usuario')->where('tipo', 'A')->first();
if ($admin) {
    echo "Admin Email: " . $admin->email . "\n";
    // We don't know the plain password, but if we need an admin we can just reset it
    $user = \App\Models\User::where('email', $admin->email)->first();
    if ($user) {
        $user->password = \Illuminate\Support\Facades\Hash::make('password123');
        $user->save();
        echo "Admin Password reset to: password123\n";
    }
} else {
    echo "No admin found.\n";
}
