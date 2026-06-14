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
                // Obtener los estudiantes del grupo con sus notas en la materia
                $estudiantes = DB::table('inscribe')
                    ->join('postulante', 'inscribe.cipostulante', '=', 'postulante.ciusuario')
                    ->join('usuario', 'postulante.ciusuario', '=', 'usuario.ci')
                    ->leftJoin('calificacion', function($join) use ($materiaSeleccionada) {
                        $join->on('inscribe.cipostulante', '=', 'calificacion.cipostulante')
                             ->where('calificacion.idmateria', '=', $materiaSeleccionada->id);
                    })
                    ->where('inscribe.codigogrupo', $grupoSeleccionado->codigo)
                    ->select(
                        'usuario.ci',
                        'usuario.nombre',
                        'usuario.apellidopat',
                        'usuario.apellidomat',
                        'calificacion.nota1',
                        'calificacion.nota2',
                        'calificacion.nota3',
                        'calificacion.nota4',
                        'calificacion.nota5',
                        'calificacion.notafinal'
                    )
                    ->get();
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

        // 1. Calcular promedio para cada postulante
        DB::transaction(function () use ($postulaciones, $promedios) {
            foreach ($postulaciones as $p) {
                $promedio_calc = $promedios->get($p->codpost, 0);
                
                $p->promedio = round($promedio_calc, 2);
                
                if ($p->promedio >= 60) {
                    $p->estado_admision = 'APROBADO_PENDIENTE';
                } else {
                    $p->estado_admision = 'REPROBADO';
                }
                $p->carrera_admitida = null;
                $p->save();
            }
        });

        // 2. Asignar cupos a los aprobados ordenados por mejor promedio
        $aprobados = \App\Models\Postulacion::where('estado_admision', 'APROBADO_PENDIENTE')
                        ->orderBy('promedio', 'desc')
                        ->get();
                        
        // Traer cupos disponibles por carrera (asumiendo que en 'ofrece' se declaran los cupos)
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

        DB::transaction(function () use ($aprobados, &$cupos_disponibles, $todasInscripciones) {
            foreach ($aprobados as $p) {
                $inscripciones = $todasInscripciones->get($p->codpost, collect());
                
                $admitido = false;
                foreach ($inscripciones as $ins) {
                    $carrera = $ins->codigocarrera;
                    if (isset($cupos_disponibles[$carrera]) && $cupos_disponibles[$carrera] > 0) {
                        $p->estado_admision = 'ADMITIDO';
                        $p->carrera_admitida = $carrera;
                        $p->save();
                        
                        $cupos_disponibles[$carrera]--;
                        $admitido = true;
                        break;
                    }
                }
                
                if (!$admitido) {
                    $p->estado_admision = 'APROBADO_SIN_CUPO';
                    $p->save();
                }
            }
        });

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
                    \Illuminate\Support\Facades\Mail::to($usuario->email)
                        ->send(new \App\Mail\ResultadoAdmisionMail($usuario, $p));
                    $count++;
                } catch (\Exception $e) {
                    // Ignorar errores de envío individual para no detener el proceso
                }
            }
        }
        
        return redirect()->back()->with('success', "Notificaciones enviadas por correo a {$count} estudiantes.");
    }
}
