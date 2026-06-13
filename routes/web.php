<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Illuminate\Support\Facades\Auth::user();
    $usuario = \App\Models\Usuario::where('email', $user->email)->first();

    if (!$usuario) {
        if ($user->role === 'docente') {
            return view('docente.dashboard');
        }
        return view('dashboard');
    }

    if ($usuario->tipo === 'D') {
        // Dashboard exclusivo y distinto para docentes (Incluso si están pendientes, solo el menú se restringe)
        return view('docente.dashboard');
    }

    // Dashboard por defecto (Estudiantes o genérico)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Registro específico para docentes
Route::get('/registro-docente', [\App\Http\Controllers\Auth\DocenteRegistrationController::class, 'create'])->name('docente.registro');
Route::post('/registro-docente', [\App\Http\Controllers\Auth\DocenteRegistrationController::class, 'store'])->name('docente.registro.store');

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
        if ($usuario && $usuario->tipo === 'D') {
            $docente = \App\Models\Docente::where('ciusuario', $usuario->ci)->first();
            if ($docente && $docente->estado === 'APROBADO') {
                return redirect()->route('dashboard');
            }
        }
        return view('docente.pendiente');
    })->name('docente.pendiente');
    
    // Ficha de Inscripción para Docentes
    Route::get('/docente/inscripcion', [\App\Http\Controllers\Auth\DocenteRegistrationController::class, 'createFicha'])->name('docente.inscripcion.create');
    Route::post('/docente/inscripcion', [\App\Http\Controllers\Auth\DocenteRegistrationController::class, 'storeFicha'])->name('docente.inscripcion.store');
    
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
    Route::get('/docente/mi-materia', [\App\Http\Controllers\DocenteDashboardController::class, 'miMateria'])->name('docente.mi_materia');
    Route::post('/docente/mi-materia/{id}', [\App\Http\Controllers\DocenteDashboardController::class, 'updateMateria'])->name('docente.mi_materia.update');
    Route::get('/docente/mis-grupos', [\App\Http\Controllers\DocenteDashboardController::class, 'misGrupos'])->name('docente.mis_grupos');
    Route::post('/docente/mis-grupos/whatsapp', [\App\Http\Controllers\DocenteDashboardController::class, 'updateWhatsapp'])->name('docente.mis_grupos.whatsapp');
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
    Route::post('/grupos/store', [\App\Http\Controllers\Admin\GrupoController::class, 'store'])->name('grupos.store');
    Route::get('/grupos/{codigo}/edit', [\App\Http\Controllers\Admin\GrupoController::class, 'edit'])->name('grupos.edit');
    Route::put('/grupos/{codigo}', [\App\Http\Controllers\Admin\GrupoController::class, 'update'])->name('grupos.update');
    Route::get('/grupos/{id}', [\App\Http\Controllers\Admin\GrupoController::class, 'show'])->name('grupos.show');
    
    // Reportes
    Route::get('/reportes', [\App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('reportes.index');
    
    // Docentes
    Route::get('/docentes', [\App\Http\Controllers\Admin\DocenteController::class, 'index'])->name('docentes.index');
    Route::get('/docentes/create', [\App\Http\Controllers\Admin\DocenteController::class, 'create'])->name('docentes.create');
    Route::post('/docentes', [\App\Http\Controllers\Admin\DocenteController::class, 'store'])->name('docentes.store');
    Route::get('/docentes/{ci}/edit', [\App\Http\Controllers\Admin\DocenteController::class, 'edit'])->name('docentes.edit');
    Route::put('/docentes/{ci}', [\App\Http\Controllers\Admin\DocenteController::class, 'update'])->name('docentes.update');
    Route::post('/docentes/{ci}/aprobar', [\App\Http\Controllers\Admin\DocenteController::class, 'aprobar'])->name('docentes.aprobar');
    Route::post('/docentes/{ci}/rechazar', [\App\Http\Controllers\Admin\DocenteController::class, 'rechazar'])->name('docentes.rechazar');
    Route::post('/docentes/auto-asignar', [\App\Http\Controllers\Admin\DocenteController::class, 'autoAsignar'])->name('docentes.auto_asignar');
    Route::delete('/docentes/{ci}', [\App\Http\Controllers\Admin\DocenteController::class, 'destroy'])->name('docentes.destroy');
    Route::get('/docentes/{ci}/asignar-materia', [\App\Http\Controllers\Admin\DocenteController::class, 'asignarMateriaForm'])->name('docentes.asignar');
    Route::post('/docentes/{ci}/asignar-materia', [\App\Http\Controllers\Admin\DocenteController::class, 'asignarMateria'])->name('docentes.asignar_materia');
    Route::delete('/docentes/{ci}/remover-materia/{codigogrupo}/{idmateria}', [\App\Http\Controllers\Admin\DocenteController::class, 'removerMateria'])->name('docentes.remover_materia');

    // Bitácora
    Route::get('/bitacora', [\App\Http\Controllers\Admin\BitacoraController::class, 'index'])->name('bitacora.index');

    // Exámenes y Notas (Admin)
    Route::get('/examenes', [\App\Http\Controllers\Admin\ExamenController::class, 'index'])->name('examenes.index');
    Route::post('/examenes/update', [\App\Http\Controllers\Admin\ExamenController::class, 'update'])->name('examenes.update');
});

