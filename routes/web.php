<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-verificar', function () {
    try {
        $ci = '55555';
        $pago = \App\Models\Pago::where('ciusuario', $ci)->first();
        $postulante = \App\Models\Postulante::where('ciusuario', $ci)->first();
        $usuario = \App\Models\Usuario::where('ci', $ci)->first();

        echo "Pago: " . ($pago ? $pago->id : 'NO') . "<br>";
        echo "Postulante: " . ($postulante ? $postulante->ciusuario : 'NO') . "<br>";

        Illuminate\Support\Facades\DB::transaction(function () use ($postulante, $usuario, $ci, $pago) {
            $postulacion = \App\Models\Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
            if ($postulacion) {
                $postulacion->idpago = $pago->id;
                
                if (is_null($postulacion->codgrupo)) {
                    $postulacion->codgrupo = 'G1'; // Simplificado
                }
                
                $postulacion->save();
            }

            $postulante->estadodocum = 'INSCRITO';
            $postulante->save();

            $authUser = \App\Models\User::where('email', $usuario->email)->first();
            if ($authUser) {
                $authUser->password = \Illuminate\Support\Facades\Hash::make($ci);
                $authUser->role = 'postulante';
                $authUser->save();
            }
            echo "Guardado ok.<br>";
        });
    } catch (\Exception $e) {
        echo "EXCEPCION: " . $e->getMessage() . "<br>";
    }
});

Route::get('/debug-check', function () {
    $ci = '55555';
    $pago = \App\Models\Pago::where('ciusuario', $ci)->first();
    $postulante = \App\Models\Postulante::where('ciusuario', $ci)->first();
    $postulacion = \App\Models\Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();

    echo "Pago ID: " . ($pago ? $pago->id : 'NULL') . "<br>";
    echo "Estado: " . ($postulante ? $postulante->estadodocum : 'NULL') . "<br>";
    echo "Postulacion ID Pago: " . ($postulacion ? $postulacion->idpago : 'NULL') . "<br>";
});

Route::get('/debug-reset', function () {
    try {
        \Illuminate\Support\Facades\DB::table('pago')->delete();
        $ci = '55555';
        $postulacion = \App\Models\Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
        if ($postulacion) {
            $postulacion->idpago = null;
            $postulacion->codgrupo = null;
            $postulacion->save();
        }
        $postulante = \App\Models\Postulante::where('ciusuario', $ci)->first();
        if ($postulante) {
            $postulante->estadodocum = 'APROBADO';
            $postulante->save();
        }
        $user = \App\Models\User::where('email', 'andradenovamarcosdavid@gmail.com')->first();
        if ($user) {
            $user->role = 'user';
            $user->save();
        }
        echo "RESET COMPLETO<br>";
    } catch (\Exception $e) {
        echo "EXCEPCION: " . $e->getMessage();
    }
});

Route::get('/debug-auto', function () {
    try {
        $usuario = \App\Models\Usuario::where('ci', '55555')->first();
        $postulante = \App\Models\Postulante::where('ciusuario', '55555')->first();

        if ($postulante->estadodocum === 'APROBADO' || $postulante->estadodocum === 'VERIFICADO') {
            $pago = \App\Models\Pago::where('ciusuario', $usuario->ci)->first();
            if ($pago) {
                // Proceder a inscribir automáticamente
                \Illuminate\Support\Facades\DB::transaction(function () use ($postulante, $usuario, $pago) {
                    $ci = $usuario->ci;
                    $postulacion = \App\Models\Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
                    if ($postulacion) {
                        $postulacion->idpago = $pago->id;
                        
                        if (is_null($postulacion->codgrupo)) {
                            $grupos = \App\Models\Grupo::all();
                            $grupoDisponible = null;
                            foreach ($grupos as $g) {
                                $cupo = $g->cupo ?? 70;
                                $inscritos = \App\Models\Postulacion::where('codgrupo', $g->codigo)->count();
                                if ($inscritos < $cupo) {
                                    $grupoDisponible = $g;
                                    break;
                                }
                            }
                            if (!$grupoDisponible) {
                                $countGrupos = \App\Models\Grupo::count();
                                $nuevoCodigo = 'G' . ($countGrupos + 1);
                                $grupoDisponible = \App\Models\Grupo::create([
                                    'codigo' => $nuevoCodigo,
                                    'nombre' => 'Grupo ' . $nuevoCodigo,
                                    'cupo' => 70,
                                    'idturno' => 1
                                ]);
                            }
                            $postulacion->codgrupo = $grupoDisponible->codigo;
                        }
                        $postulacion->save();
                    }

                    $postulante->estadodocum = 'INSCRITO';
                    $postulante->save();

                    $authUser = \App\Models\User::where('email', $usuario->email)->first();
                    if ($authUser) {
                        $authUser->password = \Illuminate\Support\Facades\Hash::make($ci);
                        $authUser->role = 'postulante';
                        $authUser->save();
                    }
                    echo "AUTO INSCRITO OK<br>";
                });
            } else {
                echo "NO PAGO<br>";
            }
        } else {
            echo "NO APROBADO: " . $postulante->estadodocum . "<br>";
        }
    } catch (\Exception $e) {
        echo "EXC: " . $e->getMessage() . "<br>";
    }
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
    
    // Rutas para el flujo principal de inscripción del estudiante (Aisladas al admin ahora)
    // Route::get('/inscripcion', [\App\Http\Controllers\InscripcionController::class, 'create'])->name('inscripcion.create');
    // Route::post('/inscripcion', [\App\Http\Controllers\InscripcionController::class, 'store'])->name('inscripcion.store');
    
    Route::get('/inscripcion/estado', [\App\Http\Controllers\InscripcionController::class, 'estado'])->name('inscripcion.estado');
    Route::post('/inscripcion/verificar-pago', [\App\Http\Controllers\InscripcionController::class, 'verificarPago'])->name('inscripcion.verificar_pago');
    
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
    Route::get('/docente/mi-horario', [\App\Http\Controllers\DocenteDashboardController::class, 'miHorario'])->name('docente.mi_horario');
    Route::get('/docente/cronograma', [\App\Http\Controllers\DocenteDashboardController::class, 'cronograma'])->name('docente.cronograma');
    Route::get('/docente/calificaciones', [\App\Http\Controllers\DocenteDashboardController::class, 'calificaciones'])->name('docente.calificaciones');
    Route::post('/docente/calificaciones', [\App\Http\Controllers\DocenteDashboardController::class, 'updateCalificaciones'])->name('docente.calificaciones.update');
});

// Pasarela de Pagos (Pública pero mediante enlace firmado)
Route::get('/pago/{ci}', [\App\Http\Controllers\PagoController::class, 'showPasarela'])->name('pago.pasarela');
Route::post('/pago/{ci}', [\App\Http\Controllers\PagoController::class, 'procesarPago'])->name('pago.procesar');

require __DIR__.'/auth.php';

Route::get('/debug-docente-cols', function () {
    $cols = \Illuminate\Support\Facades\DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'docente'");
    echo json_encode($cols);
});

Route::get('/debug-force-inscrito', function () {
    try {
        $ci = '55555';
        $postulante = \App\Models\Postulante::where('ciusuario', $ci)->first();
        $usuario = \App\Models\Usuario::where('ci', $ci)->first();

        if ($postulante && $usuario) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($postulante, $usuario, $ci) {
                $postulacion = \App\Models\Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
                if ($postulacion) {
                    if (is_null($postulacion->codgrupo)) {
                        $postulacion->codgrupo = 'G1'; 
                    }
                    $postulacion->save();
                }

                $postulante->estadodocum = 'INSCRITO';
                $postulante->save();

                $authUser = \App\Models\User::where('email', $usuario->email)->first();
                if ($authUser) {
                    $authUser->password = \Illuminate\Support\Facades\Hash::make($ci);
                    $authUser->role = 'postulante';
                    $authUser->save();
                }
            });
            echo "ESTUDIANTE FORZADO A OFICIAL (INSCRITO). Ya puede ver su dashboard de postulante.";
        } else {
            echo "Estudiante no encontrado.";
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
});

// Rutas protegidas exclusivamente para el rol de administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/postulantes', [\App\Http\Controllers\AdminController::class, 'index'])->name('postulantes');
    Route::get('/postulantes/create', [\App\Http\Controllers\AdminController::class, 'create'])->name('postulantes.create');
    Route::post('/postulantes', [\App\Http\Controllers\AdminController::class, 'store'])->name('postulantes.store');
    Route::post('/postulantes/{ci}/aprobar', [\App\Http\Controllers\AdminController::class, 'aprobarDocumentos'])->name('postulantes.aprobar');
    Route::post('/postulantes/{ci}/rechazar', [\App\Http\Controllers\AdminController::class, 'rechazarDocumentos'])->name('postulantes.rechazar');
    Route::post('/postulantes/{ci}/enviar-pago', [\App\Http\Controllers\AdminController::class, 'enviarEnlacePago'])->name('postulantes.enviar_pago');


    // Carreras
    Route::get('/carreras', [\App\Http\Controllers\Admin\CarreraController::class, 'index'])->name('carreras.index');
    Route::post('/carreras', [\App\Http\Controllers\Admin\CarreraController::class, 'update'])->name('carreras.update');
    
    // Grupos
    Route::get('/grupos', [\App\Http\Controllers\Admin\GrupoController::class, 'index'])->name('grupos.index');
    Route::post('/grupos/auto-asignar', [\App\Http\Controllers\Admin\GrupoController::class, 'autoAssign'])->name('grupos.auto_asignar');
    Route::post('/grupos/store', [\App\Http\Controllers\Admin\GrupoController::class, 'store'])->name('grupos.store');
    Route::get('/grupos/{codigo}/edit', [\App\Http\Controllers\Admin\GrupoController::class, 'edit'])->name('grupos.edit');
    Route::put('/grupos/{codigo}', [\App\Http\Controllers\Admin\GrupoController::class, 'update'])->name('grupos.update');
    Route::get('/grupos/{id}', [\App\Http\Controllers\Admin\GrupoController::class, 'show'])->name('grupos.show');
    
    // Horarios
    Route::resource('horarios', \App\Http\Controllers\Admin\HorarioController::class)->except(['create', 'show', 'edit']);

    
    // Reportes
    Route::get('/reportes', [\App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('reportes.index');

    // Asignación de Docentes
    Route::get('/asignacion', [\App\Http\Controllers\Admin\AsignacionController::class, 'index'])->name('asignacion.index');
    
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

    // Coordinadores
    Route::get('/coordinadores', [\App\Http\Controllers\Admin\CoordinadorController::class, 'index'])->name('coordinadores.index');
    Route::get('/coordinadores/create', [\App\Http\Controllers\Admin\CoordinadorController::class, 'create'])->name('coordinadores.create');
    Route::post('/coordinadores', [\App\Http\Controllers\Admin\CoordinadorController::class, 'store'])->name('coordinadores.store');
    Route::get('/coordinadores/{ci}/edit', [\App\Http\Controllers\Admin\CoordinadorController::class, 'edit'])->name('coordinadores.edit');
    Route::put('/coordinadores/{ci}', [\App\Http\Controllers\Admin\CoordinadorController::class, 'update'])->name('coordinadores.update');
    Route::delete('/coordinadores/{ci}', [\App\Http\Controllers\Admin\CoordinadorController::class, 'destroy'])->name('coordinadores.destroy');

    // Bitácora
    Route::get('/bitacora', [\App\Http\Controllers\Admin\BitacoraController::class, 'index'])->name('bitacora.index');

    // Evaluaciones (Consulta para Coordinador y Admin)
    Route::get('/evaluaciones', [\App\Http\Controllers\Admin\EvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::post('/evaluaciones/calcular', [\App\Http\Controllers\Admin\EvaluacionController::class, 'calcular'])->name('evaluaciones.calcular');
    Route::post('/evaluaciones/notificar', [\App\Http\Controllers\Admin\EvaluacionController::class, 'notificar'])->name('evaluaciones.notificar');

    // Exámenes y Notas (Admin)
    Route::get('/examenes', [\App\Http\Controllers\Admin\ExamenController::class, 'index'])->name('examenes.index');
    Route::post('/examenes/update-examenes', [\App\Http\Controllers\Admin\ExamenController::class, 'updateExamenes'])->name('examenes.update_examenes');
    Route::post('/examenes/update-config-notas', [\App\Http\Controllers\Admin\ExamenController::class, 'updateConfigNotas'])->name('examenes.update_config_notas');
    // Roles y Permisos (Admin)
    Route::get('/roles', [\App\Http\Controllers\Admin\RolesController::class, 'index'])->name('roles.index');
    Route::post('/roles/{id}', [\App\Http\Controllers\Admin\RolesController::class, 'update'])->name('roles.update');
});

