<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Materia;
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Usuario::where('tipo', 'D')->get();
        $materias = Materia::where('estado', 'HABILITADO')->orderBy('nombre')->get();
        
        return view('admin.docentes.index', compact('docentes', 'materias'));
    }

    public function create()
    {
        $materias = \App\Models\Materia::orderBy('nombre')->get();
        return view('admin.docentes.create', compact('materias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|max:15|unique:usuario,ci',
            'nombre' => 'required|string|max:100',
            'apellidopat' => 'required|string|max:100',
            'apellidomat' => 'nullable|string|max:100',
            'email' => 'required|email|unique:usuario,email',
            'password' => 'required|string|min:6',
            'profesion' => 'required|string|max:255',
            'nivelformacion' => 'required|string|max:255',
            'experiencia' => 'required|integer|min:0',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materia,id',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Crear Usuario
            $usuario = \App\Models\Usuario::create([
                'ci' => $request->ci,
                'nombre' => $request->nombre,
                'apellidopat' => $request->apellidopat,
                'apellidomat' => $request->apellidomat ?? '',
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'tipo' => 'D',
            ]);

            // Crear Docente (Aprobado automáticamente por el Admin)
            \App\Models\Docente::create([
                'ciusuario' => $usuario->ci,
                'coddocente' => (int) $usuario->ci,
                'profesion' => $request->profesion,
                'nivelformacion' => $request->nivelformacion,
                'experiencia' => $request->experiencia,
                'codrol' => 2,
                'estado' => 'APROBADO',
            ]);

            // Guardar preferencias de materias
            foreach ($request->materias as $materiaId) {
                \Illuminate\Support\Facades\DB::table('preferenciamat')->insert([
                    'cidocente' => $usuario->ci,
                    'idmateria' => $materiaId
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.docentes.index')->with('success', 'Docente registrado y aprobado exitosamente.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.docentes.index')->with('error', 'Error al crear docente: ' . $e->getMessage());
        }
    }

    public function edit($ci)
    {
        $usuario = \App\Models\Usuario::where('ci', $ci)->firstOrFail();
        $docente = \App\Models\Docente::where('ciusuario', $ci)->first();
        $materias = \App\Models\Materia::orderBy('nombre')->get();
        $preferencias = \Illuminate\Support\Facades\DB::table('preferenciamat')->where('cidocente', $ci)->pluck('idmateria')->toArray();
        return view('admin.docentes.edit', compact('usuario', 'docente', 'materias', 'preferencias'));
    }

    public function update(Request $request, $ci)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidopat' => 'required|string|max:100',
            'email' => 'required|email',
            'profesion' => 'required|string|max:255',
            'nivelformacion' => 'required|string|max:255',
            'experiencia' => 'required|integer|min:0',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materia,id',
        ]);

        $usuario = \App\Models\Usuario::where('ci', $ci)->firstOrFail();
        $usuario->update([
            'nombre' => $request->nombre,
            'apellidopat' => $request->apellidopat,
            'apellidomat' => $request->apellidomat ?? '',
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $usuario->update(['password' => bcrypt($request->password)]);
        }

        $docente = \App\Models\Docente::where('ciusuario', $ci)->first();
        if ($docente) {
            $docente->update([
                'profesion' => $request->profesion,
                'nivelformacion' => $request->nivelformacion,
                'experiencia' => $request->experiencia,
            ]);

            // Actualizar preferencias de materias
            \Illuminate\Support\Facades\DB::table('preferenciamat')->where('cidocente', $ci)->delete();
            foreach ($request->materias as $materiaId) {
                \Illuminate\Support\Facades\DB::table('preferenciamat')->insert([
                    'cidocente' => $ci,
                    'idmateria' => $materiaId
                ]);
            }
        }

        return redirect()->route('admin.docentes.index')->with('success', 'Información del docente actualizada correctamente.');
    }

    public function aprobar($ci)
    {
        $docente = \App\Models\Docente::where('ciusuario', $ci)->firstOrFail();
        $docente->estado = 'APROBADO';
        $docente->save();

        return redirect()->route('admin.docentes.index')->with('success', 'Docente aprobado correctamente.');
    }

    public function rechazar($ci)
    {
        $docente = \App\Models\Docente::where('ciusuario', $ci)->firstOrFail();
        $docente->estado = 'RECHAZADO';
        $docente->save();

        // Eliminar las asignaciones de materia/grupo que tuviera
        \Illuminate\Support\Facades\DB::table('grupodocente')->where('ciusuario', $ci)->delete();

        return redirect()->route('admin.docentes.index')->with('success', 'Docente rechazado.');
    }

    public function asignarMateriaForm($ci)
    {
        $usuario = \App\Models\Usuario::where('ci', $ci)->firstOrFail();
        $docente = \App\Models\Docente::where('ciusuario', $ci)->firstOrFail();
        
        $materias = \App\Models\Materia::where('estado', 'HABILITADO')->orderBy('nombre')->get();
        $grupos = \App\Models\Grupo::orderBy('codigo')->get();
        $horarios = \Illuminate\Support\Facades\DB::table('horario')->get();
        
        $asignaciones = \Illuminate\Support\Facades\DB::table('grupodocente')
            ->join('materia', 'grupodocente.idmateria', '=', 'materia.id')
            ->join('horario', 'grupodocente.idhorario', '=', 'horario.id')
            ->where('ciusuario', $ci)
            ->select('grupodocente.*', 'materia.nombre as materia_nombre', 'horario.dia', 'horario.iniciohorario', 'horario.finhorario', 'horario.nroaula')
            ->get();

        return view('admin.docentes.asignar', compact('usuario', 'docente', 'materias', 'grupos', 'horarios', 'asignaciones'));
    }

    public function asignarMateria(Request $request, $ci)
    {
        $request->validate([
            'materia_id' => ['required', Rule::exists('materia', 'id')->where('estado', 'HABILITADO')],
            'grupo_codigo' => 'required|exists:grupo,codigo',
            'horario_id' => 'required|exists:horario,id',
        ]);

        $docente = \App\Models\Docente::where('ciusuario', $ci)->firstOrFail();
        
        // Verificar si este grupo ya tiene un docente para esta materia
        $ocupado = \Illuminate\Support\Facades\DB::table('grupodocente')
            ->where('codigogrupo', $request->grupo_codigo)
            ->where('idmateria', $request->materia_id)
            ->first();

        if ($ocupado) {
            return redirect()->back()->with('error', 'Ese grupo ya tiene un docente asignado para esa materia.');
        }

        // Verificar cruce de horarios para el docente
        $cruce = \Illuminate\Support\Facades\DB::table('grupodocente')
            ->where('ciusuario', $ci)
            ->where('idhorario', $request->horario_id)
            ->first();
            
        if ($cruce) {
            return redirect()->back()->with('error', 'El docente ya tiene una clase asignada en ese horario.');
        }

        \Illuminate\Support\Facades\DB::table('grupodocente')->insert([
            'codigogrupo' => $request->grupo_codigo,
            'ciusuario' => $docente->ciusuario,
            'idmateria' => $request->materia_id,
            'idhorario' => $request->horario_id
        ]);

        return redirect()->back()->with('success', 'Carga horaria asignada al docente correctamente.');
    }
    
    public function removerMateria($ci, $codigogrupo, $idmateria)
    {
        \Illuminate\Support\Facades\DB::table('grupodocente')
            ->where('ciusuario', $ci)
            ->where('codigogrupo', $codigogrupo)
            ->where('idmateria', $idmateria)
            ->delete();
            
        return redirect()->back()->with('success', 'Asignación removida correctamente.');
    }

    public function destroy($ci)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Eliminar asignaciones de horarios
            \Illuminate\Support\Facades\DB::table('grupodocente')->where('ciusuario', $ci)->delete();
            // Eliminar de tabla docente
            \Illuminate\Support\Facades\DB::table('docente')->where('ciusuario', $ci)->delete();
            // Eliminar usuario
            \App\Models\Usuario::where('ci', $ci)->delete();

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.docentes.index')->with('success', 'Docente eliminado permanentemente del sistema.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.docentes.index')->with('error', 'No se pudo eliminar al docente: ' . $e->getMessage());
        }
    }

    public function autoAsignar()
    {
        $grupos = \App\Models\Grupo::all();
        $materias = \App\Models\Materia::whereIn('id', [1, 2, 3, 4])->get();
        $docentesAprobados = \App\Models\Docente::where('estado', 'APROBADO')->get();

        if ($docentesAprobados->count() < 4) {
            return redirect()->route('admin.docentes.index')->with('error', 'Se necesitan al menos 4 docentes aprobados para la auto-asignación.');
        }

        $turnos = [
            'Manana' => ['07:00:00', '08:30:00', '10:00:00', '11:30:00'],
            'Tarde'  => ['14:00:00', '15:30:00', '17:00:00', '18:30:00']
        ];
        $diasConfig = ['Lun-Mie-Vie', 'Mar-Jue-Sab'];
        $aulas = \Illuminate\Support\Facades\DB::table('aula')->pluck('nro')->toArray();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $gruposAsignados = 0;

            foreach ($grupos as $grupo) {
                // Si el grupo ya tiene alguna materia asignada, lo saltamos para evitar conflictos parciales
                $tieneAsignacion = \Illuminate\Support\Facades\DB::table('grupodocente')
                    ->where('codigogrupo', $grupo->codigo)
                    ->exists();

                if ($tieneAsignacion) {
                    continue;
                }

                $asignadoExitosamente = false;

                // Buscar una combinación libre de (Día + Turno + Aula)
                // Mezclamos para que no todos los grupos usen la misma aula o mismo turno siempre
                shuffle($diasConfig);
                $nombresTurnos = array_keys($turnos);
                shuffle($nombresTurnos);
                shuffle($aulas);

                foreach ($diasConfig as $dia) {
                    if ($asignadoExitosamente) break;

                    foreach ($nombresTurnos as $nombreTurno) {
                        if ($asignadoExitosamente) break;
                        $bloquesDelTurno = $turnos[$nombreTurno];

                        foreach ($aulas as $aula) {
                            if ($asignadoExitosamente) break;

                            // Verificar si ALGUIEN está usando esta Aula en este Día en CUALQUIERA de los bloques del Turno
                            $aulaOcupada = \Illuminate\Support\Facades\DB::table('grupodocente')
                                ->join('horario', 'grupodocente.idhorario', '=', 'horario.id')
                                ->where('horario.dia', $dia)
                                ->where('horario.nroaula', $aula)
                                ->whereIn('horario.iniciohorario', $bloquesDelTurno)
                                ->exists();

                            if ($aulaOcupada) {
                                continue; // Buscar otra aula
                            }

                            // ¡El Aula está libre en este Día y Turno completo!
                            // Ahora intentamos encontrar 4 docentes libres para los 4 bloques
                            $docentesParaGrupo = [];
                            $docentesDisponibles = clone $docentesAprobados;
                            $asignacionTemporal = [];
                            $materiasList = clone $materias;

                            $posible = true;

                            for ($i = 0; $i < 4; $i++) {
                                $bloque = $bloquesDelTurno[$i];
                                $materiaActual = $materiasList[$i];

                                // Encontrar un docente libre en este bloque que dicte la materia actual
                                $docenteLibreEncontrado = false;
                                
                                foreach ($docentesDisponibles->shuffle() as $docente) {
                                    // RESTRICCIÓN: El docente debe ser capaz de enseñar esta materia (según preferenciamat)
                                    $puedeEnsenar = \Illuminate\Support\Facades\DB::table('preferenciamat')
                                        ->where('cidocente', $docente->ciusuario)
                                        ->where('idmateria', $materiaActual->id)
                                        ->exists();

                                    if (!$puedeEnsenar) {
                                        continue;
                                    }

                                    // Verificar que el docente no esté dando clases en este exacto Día y Bloque (puede estar en otra aula)
                                    $docenteOcupado = \Illuminate\Support\Facades\DB::table('grupodocente')
                                        ->join('horario', 'grupodocente.idhorario', '=', 'horario.id')
                                        ->where('grupodocente.ciusuario', $docente->ciusuario)
                                        ->where('horario.dia', $dia)
                                        ->where('horario.iniciohorario', $bloque)
                                        ->exists();

                                    if (!$docenteOcupado) {
                                        // Buscar el ID del horario exacto
                                        $horarioModel = \Illuminate\Support\Facades\DB::table('horario')
                                            ->where('dia', $dia)
                                            ->where('nroaula', $aula)
                                            ->where('iniciohorario', $bloque)
                                            ->first();

                                        if ($horarioModel) {
                                            $asignacionTemporal[] = [
                                                'codigogrupo' => $grupo->codigo,
                                                'ciusuario' => $docente->ciusuario,
                                                'idmateria' => $materiaActual->id,
                                                'idhorario' => $horarioModel->id
                                            ];
                                            $docenteLibreEncontrado = true;
                                            
                                            // Sacar a este docente de la lista para que no dé dos materias seguidas al mismo grupo (opcional)
                                            $docentesDisponibles = $docentesDisponibles->reject(function ($val) use ($docente) {
                                                return $val->ciusuario === $docente->ciusuario;
                                            });
                                            break;
                                        }
                                    }
                                }

                                if (!$docenteLibreEncontrado) {
                                    $posible = false;
                                    break; // No se encontró docente para este bloque
                                }
                            }

                            if ($posible) {
                                // Insertar las 4 asignaciones contiguas
                                foreach ($asignacionTemporal as $asig) {
                                    \Illuminate\Support\Facades\DB::table('grupodocente')->insert($asig);
                                }
                                $asignadoExitosamente = true;
                                $gruposAsignados++;
                            }
                        }
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            
            if ($gruposAsignados > 0) {
                return redirect()->route('admin.docentes.index')->with('success', "Auto-asignación completada: Se asignaron bloques de horarios continuos a $gruposAsignados grupo(s).");
            } else {
                return redirect()->route('admin.docentes.index')->with('info', 'No se realizaron asignaciones nuevas. Es probable que los grupos ya estén llenos o no hayan docentes libres para cubrir un turno completo.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.docentes.index')->with('error', 'Error en la auto-asignación: ' . $e->getMessage());
        }
    }
}
