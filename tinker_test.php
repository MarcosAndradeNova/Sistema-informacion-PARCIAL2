<?php
try {
    $user = App\Models\Usuario::where('rol', 'A')->first();
    Auth::login($user);
    $view = app(App\Http\Controllers\Admin\GrupoController::class)->index();
    echo "Grupo View length: " . strlen($view->render()) . "\n";
} catch (\Throwable $e) {
    echo "Grupo Error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile() . "\n";
}

try {
    $view2 = app(App\Http\Controllers\Admin\ReporteController::class)->index();
    echo "Reporte View length: " . strlen($view2->render()) . "\n";
} catch (\Throwable $e) {
    echo "Reporte Error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile() . "\n";
}
