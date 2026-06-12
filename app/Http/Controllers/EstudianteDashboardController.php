<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Postulacion;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\ResultadoExam;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\GrupoDocente;
use Illuminate\Support\Facades\DB;

class EstudianteDashboardController extends Controller
{
    private function getPostulante()
    {
        $usuario = Usuario::where('email', Auth::user()->email)->first();
        if (!$usuario) return null;
        return Postulante::where('ciusuario', $usuario->ci)->first();
    }

    private function sembrarAulas()
    {
        if (Aula::count() == 0) {
            $aulas = [];
            foreach ([11, 21, 31, 41] as $piso) {
                for ($i = 0; $i <= 5; $i++) {
                    $aulas[] = ['nro' => (string)($piso + $i), 'capacidad' => 70];
                }
            }
            Aula::insert($aulas);
        }
    }

    private function sembrarMaterias()
    {
        // Asegurarse que las 4 materias existen
        if (Materia::count() < 4) {
            $materias = [
                ['id' => 1, 'nombre' => 'Matemáticas'],
                ['id' => 2, 'nombre' => 'Física'],
                ['id' => 3, 'nombre' => 'Inglés'],
                ['id' => 4, 'nombre' => 'Computación']
            ];
            foreach ($materias as $m) {
                Materia::updateOrCreate(['id' => $m['id']], ['nombre' => $m['nombre']]);
            }
        }
    }

    private function generarHorarioGrupo($codigoGrupo)
    {
        $this->sembrarAulas();
        $this->sembrarMaterias();

        $grupo = Grupo::find($codigoGrupo);
        if (!$grupo) return;

        // Verificar si ya tiene horarios generados
        if (GrupoDocente::where('codigogrupo', $codigoGrupo)->exists()) {
            return;
        }

        $materias = Materia::whereIn('id', [1, 2, 3, 4])->get();
        $aulas = Aula::pluck('nro')->toArray();
        
        $dias = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
        $bloques = [
            ['inicio' => '07:00:00', 'fin' => '07:45:00'],
            ['inicio' => '07:45:00', 'fin' => '08:30:00'],
            ['inicio' => '08:30:00', 'fin' => '09:15:00'],
            ['inicio' => '09:15:00', 'fin' => '10:00:00'],
            ['inicio' => '10:00:00', 'fin' => '10:45:00'],
            ['inicio' => '10:45:00', 'fin' => '11:30:00']
        ];

        foreach ($materias as $materia) {
            $asignado = false;
            // Buscar un bloque disponible que no tenga choques para el grupo ni el aula
            foreach ($dias as $dia) {
                if ($asignado) break;
                foreach ($bloques as $bloque) {
                    if ($asignado) break;

                    // Verificar choque para el grupo en este día y bloque
                    // (Para verificar choque del grupo, vemos los horarios de sus GrupoDocente)
                    $choqueGrupo = Horario::where('dia', $dia)
                        ->where('iniciohorario', $bloque['inicio'])
                        ->whereHas('grupodocente', function($q) use ($codigoGrupo) {
                            $q->where('codigogrupo', $codigoGrupo);
                        })->exists();

                    if ($choqueGrupo) continue;

                    // Buscar un aula libre
                    foreach ($aulas as $nroAula) {
                        $aulaOcupada = Horario::where('dia', $dia)
                            ->where('iniciohorario', $bloque['inicio'])
                            ->where('nroaula', $nroAula)
                            ->exists();

                        if (!$aulaOcupada) {
                            // Asignar!
                            $horario = Horario::create([
                                'dia' => $dia,
                                'iniciohorario' => $bloque['inicio'],
                                'finhorario' => $bloque['fin'],
                                'nroaula' => $nroAula
                            ]);

                            // Insertar en grupodocente
                            // La base de datos exige un ciusuario (Not Null) y la llave primaria es (codigogrupo, ciusuario).
                            // Esto significa que un docente solo puede dar UNA materia por grupo.
                            // Buscamos un docente distinto por materia, o creamos uno "Por Asignar" específico para la materia.
                            
                            $ciDummy = '000000' . $materia->id;
                            
                            $docente = \App\Models\Usuario::where('ci', $ciDummy)->first();
                            
                            if (!$docente) {
                                $docente = \App\Models\Usuario::create([
                                    'ci' => $ciDummy,
                                    'nombre' => 'Por Asignar',
                                    'apellidopat' => 'Materia ' . $materia->id,
                                    'apellidomat' => '',
                                    'email' => 'sin_asignar_m' . $materia->id . '@ficct.edu',
                                    'tipo' => 'D',
                                    'fechanac' => '2000-01-01',
                                    'sexo' => 'M',
                                    'nacionalidad' => 'Boliviana',
                                    'direccion' => 'S/N',
                                    'telefono' => '0'
                                ]);
                            }

                            // Asegurar que también esté en la tabla docente
                            $existeDocente = \Illuminate\Support\Facades\DB::table('docente')->where('ciusuario', $docente->ci)->exists();
                            if (!$existeDocente) {
                                \Illuminate\Support\Facades\DB::table('docente')->insert([
                                    'ciusuario' => $docente->ci,
                                    'profesion' => 'Por Asignar',
                                    'codrol' => 2
                                ]);
                            }

                            GrupoDocente::insert([
                                'codigogrupo' => $codigoGrupo,
                                'idmateria' => $materia->id,
                                'idhorario' => $horario->id,
                                'ciusuario' => $docente->ci
                            ]);

                            $asignado = true;
                            break;
                        }
                    }
                }
            }
        }
    }

    public function miGrupo()
    {
        $postulante = $this->getPostulante();
        if (!$postulante) {
            return redirect()->route('dashboard')->with('error', 'No se encontró registro de postulante.');
        }

        // Buscar postulacion activa
        $postulacion = Postulacion::where('ciusuario', $postulante->ciusuario)->first();

        if (!$postulacion || !$postulacion->codgrupo) {
            // Asignación automática de grupo
            $grupos = Grupo::all();
            $grupoDisponible = null;
            
            foreach ($grupos as $g) {
                $inscritos = Postulacion::where('codgrupo', $g->codigo)->count();
                if ($inscritos < $g->cupo) {
                    $grupoDisponible = $g;
                    break;
                }
            }

            // Si no hay grupos con espacio, crear uno nuevo
            if (!$grupoDisponible) {
                $countGrupos = Grupo::count();
                $nuevoCodigo = 'G' . ($countGrupos + 1);
                $grupoDisponible = Grupo::create([
                    'codigo' => $nuevoCodigo,
                    'nombre' => 'Grupo ' . $nuevoCodigo,
                    'cupo' => 70, // Capacidad por defecto del aula
                    'idturno' => 1
                ]);
            }

            if (!$postulacion) {
                // Generar un ID único para la llave primaria
                $maxId = Postulacion::max('codpost') ?? 0;
                $postulacion = Postulacion::create([
                    'codpost' => $maxId + 1,
                    'fecha' => date('Y-m-d'),
                    'hora' => date('H:i:s'),
                    'ciusuario' => $postulante->ciusuario,
                    'codgrupo' => $grupoDisponible->codigo
                ]);
            } else {
                $postulacion->codgrupo = $grupoDisponible->codigo;
                $postulacion->save();
            }
        }

        $grupo = Grupo::findOrFail($postulacion->codgrupo);
        
        // Buscar compañeros del mismo grupo
        $companerosPostulaciones = Postulacion::with('postulante.usuario')
            ->where('codgrupo', $grupo->codigo)
            ->where('ciusuario', '!=', $postulante->ciusuario)
            ->get();
        
        // Extraer los compañeros
        $companeros = $companerosPostulaciones->map(function ($p) {
            return $p->postulante;
        })->filter();

        // Obtener horarios generados para el grupo
        $grupodocentes = GrupoDocente::with(['materia', 'horario'])->where('codigogrupo', $grupo->codigo)->get();

        return view('estudiante.mi_grupo', compact('grupo', 'companeros', 'postulante', 'grupodocentes'));
    }

    public function misMaterias()
    {
        $postulante = $this->getPostulante();
        if (!$postulante) {
            return redirect()->route('dashboard');
        }

        $materias = Materia::all();
        
        return view('estudiante.mis_materias', compact('materias'));
    }

    public function misExamenes()
    {
        $postulante = $this->getPostulante();
        if (!$postulante) {
            return redirect()->route('dashboard');
        }

        // Obtener la postulación para saber el grupo
        $postulacion = Postulacion::where('ciusuario', $postulante->ciusuario)->first();
        
        // Exámenes globales (1, 2, 3)
        $examenesGlobales = DB::table('examen')->orderBy('nro')->get();
        
        $cronograma = collect();

        if ($postulacion && $postulacion->codgrupo) {
            // Obtenemos los registros de grupodocente para saber las materias y aulas de este grupo
            $grupoDocentes = GrupoDocente::with(['materia', 'horario'])
                ->where('codigogrupo', $postulacion->codgrupo)
                ->get();
            
            // Para cada materia asignada al grupo, creamos 3 entradas de exámenes
            foreach ($grupoDocentes as $gd) {
                if ($gd->materia) {
                    $aula = $gd->horario ? $gd->horario->nroaula : 'Por Asignar';
                    
                    foreach ($examenesGlobales as $examen) {
                        $fechaEspecifica = $gd->{'fecha_examen'.$examen->nro} ?? $examen->fecha;
                        $cronograma->push((object)[
                            'materia' => $gd->materia->nombre,
                            'nro_examen' => $examen->nro,
                            'descripcion' => $examen->descripcion . ' - ' . $gd->materia->nombre,
                            'fecha' => $fechaEspecifica,
                            'aula' => $aula
                        ]);
                    }
                }
            }
        }

        // Si el grupo no tiene materias asignadas o el estudiante no tiene grupo, 
        // mostraremos los exámenes genéricos (solo los 3) o vacío.
        if ($cronograma->isEmpty() && $examenesGlobales->isNotEmpty()) {
            foreach ($examenesGlobales as $examen) {
                $cronograma->push((object)[
                    'materia' => 'General',
                    'nro_examen' => $examen->nro,
                    'descripcion' => $examen->descripcion,
                    'fecha' => $examen->fecha,
                    'aula' => 'Por Asignar'
                ]);
            }
        }

        // Obtener resultados del estudiante para la libreta
        $resultadosDB = ResultadoExam::with('materia')
            ->where('ciusuario', $postulante->ciusuario)
            ->get();

        $materiasAgrupadas = [];
        
        foreach ($resultadosDB as $res) {
            $idMateria = $res->idmateria;
            if (!isset($materiasAgrupadas[$idMateria])) {
                $materiasAgrupadas[$idMateria] = [
                    'materia' => $res->materia ? $res->materia->nombre : 'Desconocida',
                    'nota1' => null,
                    'nota2' => null,
                    'nota3' => null
                ];
            }
            
            if ($res->nroexamen == 1) $materiasAgrupadas[$idMateria]['nota1'] = $res->calificacion;
            elseif ($res->nroexamen == 2) $materiasAgrupadas[$idMateria]['nota2'] = $res->calificacion;
            elseif ($res->nroexamen == 3) $materiasAgrupadas[$idMateria]['nota3'] = $res->calificacion;
        }

        $calificaciones = collect();
        foreach ($materiasAgrupadas as $id => $data) {
            $n1 = $data['nota1'] ?? 0;
            $n2 = $data['nota2'] ?? 0;
            $n3 = $data['nota3'] ?? 0;
            
            $promedio = ($n1 + $n2 + $n3) / 3;
            $estado = $promedio >= 51 ? 'Aprobado' : 'Reprobado';
            
            $calificaciones->push((object)[
                'materia' => $data['materia'],
                'nota1' => $data['nota1'] === null ? '-' : round($data['nota1'], 2),
                'nota2' => $data['nota2'] === null ? '-' : round($data['nota2'], 2),
                'nota3' => $data['nota3'] === null ? '-' : round($data['nota3'], 2),
                'promedio' => round($promedio, 2),
                'estado' => $estado
            ]);
        }

        return view('estudiante.mis_examenes', compact('calificaciones', 'cronograma'));
    }
}
