<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Flujo de Inscripción de Estudiantes
    Route::get('/inscripcion', [\App\Http\Controllers\InscripcionController::class, 'create'])->name('inscripcion.create');
    Route::post('/inscripcion', [\App\Http\Controllers\InscripcionController::class, 'store'])->name('inscripcion.store');
    Route::get('/inscripcion/estado', [\App\Http\Controllers\InscripcionController::class, 'estado'])->name('inscripcion.estado');
    
    // Pasarela de Pago
    Route::get('/pago', [\App\Http\Controllers\PagoController::class, 'create'])->name('pago.create');
    Route::post('/pago', [\App\Http\Controllers\PagoController::class, 'store'])->name('pago.store');
    
    // Módulo de Registro de Postulantes
    Route::resource('postulantes', \App\Http\Controllers\Postulantes\PostulanteController::class);
    
    // Módulo de Exámenes
    Route::get('examenes', [\App\Http\Controllers\Examenes\ExamenController::class, 'index'])->name('examenes.index');
    Route::get('examenes/{ci}/edit', [\App\Http\Controllers\Examenes\ExamenController::class, 'edit'])->name('examenes.edit');
    Route::put('examenes/{ci}', [\App\Http\Controllers\Examenes\ExamenController::class, 'update'])->name('examenes.update');
});

require __DIR__.'/auth.php';

// Rutas de Administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/postulantes', [\App\Http\Controllers\AdminController::class, 'index'])->name('postulantes');
    Route::post('/postulantes/{ci}/aprobar', [\App\Http\Controllers\AdminController::class, 'aprobarDocumentos'])->name('postulantes.aprobar');
    Route::post('/postulantes/{ci}/rechazar', [\App\Http\Controllers\AdminController::class, 'rechazarDocumentos'])->name('postulantes.rechazar');
    
    // Grupos
    Route::get('/grupos', [\App\Http\Controllers\Admin\GrupoController::class, 'index'])->name('grupos.index');
    Route::post('/grupos/auto-assign', [\App\Http\Controllers\Admin\GrupoController::class, 'autoAssign'])->name('grupos.auto_assign');
    Route::get('/grupos/{id}', [\App\Http\Controllers\Admin\GrupoController::class, 'show'])->name('grupos.show');
    
    // Reportes
    Route::get('/reportes', [\App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('reportes.index');
});
