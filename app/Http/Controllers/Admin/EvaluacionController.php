<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materia;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;

class EvaluacionController extends Controller
{
    public function index(Request $request)
    {
        $materias = Materia::all();
        $grupos = Grupo::all();

        $materiaSeleccionada = null;
        $grupoSeleccionado = null;
        $estudiantes = [];

        if ($request->filled('materia_id') && $request->filled('grupo_id')) {
            $materiaSeleccionada = Materia::find($request->materia_id);
            $grupoSeleccionado = Grupo::where('codigo', $request->grupo_id)->first();

            if ($materiaSeleccionada && $grupoSeleccionado) {
                // Obtener estudiantes mediante Postulacion usando el nuevo esquema
                $postulaciones = \App\Models\Postulacion::where('codgrupo', $grupoSeleccionado->codigo)
                    ->with('usuario')
                    ->get();
                    
                $calificaciones = \App\Models\ResultadoExam::where('idmateria', $materiaSeleccionada->id)
                    ->get()
                    ->groupBy('ciusuario');

                $estudiantesCollection = collect();

                foreach ($postulaciones as $p) {
                    if (!$p->usuario) continue;
                    
                    $notas = $calificaciones->get($p->ciusuario, collect());
                    $est = new \stdClass();
                    $est->ci = $p->usuario->ci;
                    $est->nombre = $p->usuario->nombre;
                    $est->apellidopat = $p->usuario->apellidopat;
                    $est->apellidomat = $p->usuario->apellidomat;
                    
                    $est->nota1 = $notas->where('nroexamen', 1)->first()->calificacion ?? null;
                    $est->nota2 = $notas->where('nroexamen', 2)->first()->calificacion ?? null;
                    $est->nota3 = $notas->where('nroexamen', 3)->first()->calificacion ?? null;
                    $est->nota4 = null;
                    $est->nota5 = null;
                    
                    // Calculamos promedio de la materia
                    $totalNotas = 0;
                    $countNotas = 3; // Promedio entre 3 exámenes
                    if ($est->nota1 !== null) { $totalNotas += $est->nota1; }
                    if ($est->nota2 !== null) { $totalNotas += $est->nota2; }
                    if ($est->nota3 !== null) { $totalNotas += $est->nota3; }
                    
                    $est->notafinal = round($totalNotas / $countNotas, 2);
                    
                    $estudiantesCollection->push($est);
                }
                $estudiantes = $estudiantesCollection;
            }
        }

        return view('admin.evaluaciones.index', compact('materias', 'grupos', 'materiaSeleccionada', 'grupoSeleccionado', 'estudiantes'));
    }

    public function calcular(Request $request)
    {
        $postulaciones = \App\Models\Postulacion::all();

        // OPTIMIZACIÓN: Calcular promedios de una sola vez
        $promedios = DB::table('resultadoexam')
            ->select('codpost', DB::raw('avg(calificacion) as promedio'))
            ->groupBy('codpost')
            ->pluck('promedio', 'codpost');

        // 1. Calcular promedio para cada postulante usando UPSERT masivo
        $dataUpsert1 = [];
        foreach ($postulaciones as $p) {
            $row = $p->getAttributes();
            $promedio_calc = $promedios->get($p->codpost, 0);
            
            $row['promedio'] = round($promedio_calc, 2);
            if ($row['promedio'] >= 60) {
                $row['estado_admision'] = 'APROBADO_PENDIENTE';
            } else {
                $row['estado_admision'] = 'REPROBADO';
            }
            $row['carrera_admitida'] = null;
            $dataUpsert1[] = $row;
        }

        // Ejecutar upsert en trozos (chunks) para no saturar la sentencia SQL
        foreach (array_chunk($dataUpsert1, 100) as $chunk) {
            \App\Models\Postulacion::upsert($chunk, ['codpost'], ['promedio', 'estado_admision', 'carrera_admitida']);
        }

        // 2. Asignar cupos a los aprobados ordenados por mejor promedio
        $aprobados = \App\Models\Postulacion::where('estado_admision', 'APROBADO_PENDIENTE')
                        ->orderBy('promedio', 'desc')
                        ->get();
                        
        // Traer cupos disponibles por carrera
        $ofrece = DB::table('ofrece')->get();
        $cupos_disponibles = [];
        foreach ($ofrece as $o) {
            $cupos_disponibles[$o->codigocarre] = $o->cupo;
        }

        // OPTIMIZACIÓN: Traer todas las inscripciones (opciones de carrera) de golpe
        $todasInscripciones = DB::table('inscribe')
            ->orderBy('opcion', 'asc')
            ->get()
            ->groupBy('codpost');

        // Calcular cupos usando array temporal para hacer otro UPSERT masivo
        $dataUpsert2 = [];
        foreach ($aprobados as $p) {
            $row = $p->getAttributes();
            $inscripciones = $todasInscripciones->get($p->codpost, collect());
            
            $admitido = false;
            foreach ($inscripciones as $ins) {
                $carrera = $ins->codigocarrera;
                if (isset($cupos_disponibles[$carrera]) && $cupos_disponibles[$carrera] > 0) {
                    $row['estado_admision'] = 'ADMITIDO';
                    $row['carrera_admitida'] = $carrera;
                    $cupos_disponibles[$carrera]--;
                    $admitido = true;
                    break;
                }
            }
            
            if (!$admitido) {
                $row['estado_admision'] = 'APROBADO_SIN_CUPO';
            }
            $dataUpsert2[] = $row;
        }

        foreach (array_chunk($dataUpsert2, 100) as $chunk) {
            \App\Models\Postulacion::upsert($chunk, ['codpost'], ['estado_admision', 'carrera_admitida']);
        }

        return redirect()->back()->with('success', 'Los promedios y cupos de admisión fueron calculados exitosamente.');
    }

    public function notificar(Request $request)
    {
        // Eager load the usuario to avoid N+1 problem
        $postulaciones = \App\Models\Postulacion::with('usuario')->whereNotNull('estado_admision')->get();
        $count = 0;
        
        foreach ($postulaciones as $p) {
            $usuario = $p->usuario;
            if ($usuario && $usuario->email) {
                try {
                    // Usar queue() en lugar de send() para evitar colgar la página por el tiempo de red
                    \Illuminate\Support\Facades\Mail::to($usuario->email)
                        ->queue(new \App\Mail\ResultadoAdmisionMail($usuario, $p));
                    $count++;
                } catch (\Exception $e) {
                    // Ignorar errores de envío individual para no detener el proceso
                }
            }
        }
        
        return redirect()->back()->with('success', "Notificaciones puestas en cola para {$count} estudiantes. (Se enviarán en segundo plano).");
    }
}
