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
    
    // Rutas para el flujo principal de inscripción del estudiante
    Route::get('/inscripcion', [\App\Http\Controllers\InscripcionController::class, 'create'])->name('inscripcion.create');
    Route::post('/inscripcion', [\App\Http\Controllers\InscripcionController::class, 'store'])->name('inscripcion.store');
    Route::get('/inscripcion/estado', [\App\Http\Controllers\InscripcionController::class, 'estado'])->name('inscripcion.estado');
    
    // Pasarela de Pago
    Route::get('/pago', [\App\Http\Controllers\PagoController::class, 'create'])->name('pago.create');
    Route::post('/pago', [\App\Http\Controllers\PagoController::class, 'store'])->name('pago.store');
    
    // Funciones del Estudiante Activo (Postulante)
    Route::get('/mi-grupo', [\App\Http\Controllers\EstudianteDashboardController::class, 'miGrupo'])->name('estudiante.mi_grupo');
    Route::get('/mis-materias', [\App\Http\Controllers\EstudianteDashboardController::class, 'misMaterias'])->name('estudiante.mis_materias');
    Route::get('/mis-examenes', [\App\Http\Controllers\EstudianteDashboardController::class, 'misExamenes'])->name('estudiante.mis_examenes');
    
    // Vista de espera para Docentes
    Route::get('/docente/pendiente', function () {
        $usuario = \App\Models\Usuario::where('email', Auth::user()->email)->first();
        if ($usuario && $usuario->tipo === 'D' && $usuario->estado_aprobacion === 'APROBADO') {
            return redirect()->route('dashboard');
        }
        return view('docente.pendiente');
    })->name('docente.pendiente');
    
});

// Rutas protegidas exclusivamente para el rol de Docente (o Admin)
Route::middleware(['auth', 'docente'])->group(function () {
    // Módulo completo para gestionar postulantes registrados en el sistema
    Route::resource('postulantes', \App\Http\Controllers\Postulantes\PostulanteController::class);
    
    // Módulo de Exámenes
    Route::get('examenes', [\App\Http\Controllers\Examenes\ExamenController::class, 'index'])->name('examenes.index');
    Route::get('examenes/{ci}/edit', [\App\Http\Controllers\Examenes\ExamenController::class, 'edit'])->name('examenes.edit');
    Route::put('examenes/{ci}', [\App\Http\Controllers\Examenes\ExamenController::class, 'update'])->name('examenes.update');
    // Configuración de Materias (Puntos)
    Route::get('/examenes/configuracion', [\App\Http\Controllers\MateriaController::class, 'index'])->name('examenes.puntos');
    Route::post('/examenes/configuracion', [\App\Http\Controllers\MateriaController::class, 'update'])->name('examenes.puntos.update');
    
    // Panel de Control del Docente
    Route::get('/docente/mis-materias', [\App\Http\Controllers\DocenteDashboardController::class, 'misMaterias'])->name('docente.mis_materias');
    Route::post('/docente/mis-materias/{id}', [\App\Http\Controllers\DocenteDashboardController::class, 'updateMateria'])->name('docente.mis_materias.update');
    Route::get('/docente/mis-estudiantes', [\App\Http\Controllers\DocenteDashboardController::class, 'misEstudiantes'])->name('docente.mis_estudiantes');
    Route::get('/docente/cronograma', [\App\Http\Controllers\DocenteDashboardController::class, 'cronograma'])->name('docente.cronograma');
    Route::get('/docente/calificaciones', [\App\Http\Controllers\DocenteDashboardController::class, 'calificaciones'])->name('docente.calificaciones');
    Route::post('/docente/calificaciones', [\App\Http\Controllers\DocenteDashboardController::class, 'updateCalificaciones'])->name('docente.calificaciones.update');
});

require __DIR__.'/auth.php';

// Rutas protegidas exclusivamente para el rol de administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/postulantes', [\App\Http\Controllers\AdminController::class, 'index'])->name('postulantes');
    Route::post('/postulantes/{ci}/aprobar', [\App\Http\Controllers\AdminController::class, 'aprobarDocumentos'])->name('postulantes.aprobar');
    Route::post('/postulantes/{ci}/rechazar', [\App\Http\Controllers\AdminController::class, 'rechazarDocumentos'])->name('postulantes.rechazar');
    // Carreras
    Route::get('/carreras', [\App\Http\Controllers\Admin\CarreraController::class, 'index'])->name('carreras.index');
    Route::post('/carreras', [\App\Http\Controllers\Admin\CarreraController::class, 'update'])->name('carreras.update');
    
    // Grupos
    Route::get('/grupos', [\App\Http\Controllers\Admin\GrupoController::class, 'index'])->name('grupos.index');
    Route::post('/grupos/auto-assign', [\App\Http\Controllers\Admin\GrupoController::class, 'autoAssign'])->name('grupos.auto_assign');
    Route::get('/grupos/{id}', [\App\Http\Controllers\Admin\GrupoController::class, 'show'])->name('grupos.show');
    
    // Reportes
    Route::get('/reportes', [\App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('reportes.index');
    
    // Docentes
    Route::get('/docentes', [\App\Http\Controllers\Admin\DocenteController::class, 'index'])->name('docentes.index');
    Route::post('/docentes/{ci}/aprobar', [\App\Http\Controllers\Admin\DocenteController::class, 'aprobar'])->name('docentes.aprobar');
    Route::post('/docentes/{ci}/rechazar', [\App\Http\Controllers\Admin\DocenteController::class, 'rechazar'])->name('docentes.rechazar');
    Route::post('/docentes/{ci}/asignar-materia', [\App\Http\Controllers\Admin\DocenteController::class, 'asignarMateria'])->name('docentes.asignar_materia');
});
