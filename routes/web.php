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
    
    // Módulo de Registro de Postulantes
    Route::resource('postulantes', \App\Http\Controllers\Postulantes\PostulanteController::class);
    
    // Módulo de Exámenes
    Route::get('examenes', [\App\Http\Controllers\Examenes\ExamenController::class, 'index'])->name('examenes.index');
    Route::get('examenes/{ci}/edit', [\App\Http\Controllers\Examenes\ExamenController::class, 'edit'])->name('examenes.edit');
    Route::put('examenes/{ci}', [\App\Http\Controllers\Examenes\ExamenController::class, 'update'])->name('examenes.update');
});

require __DIR__.'/auth.php';
