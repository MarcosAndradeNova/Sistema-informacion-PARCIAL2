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
        
        // 1. Calcular promedio para cada postulante
        foreach ($postulaciones as $p) {
            $promedio = DB::table('resultadoexam')
                ->where('codpost', $p->codpost)
                ->avg('calificacion');
            
            $p->promedio = $promedio ? round($promedio, 2) : 0;
            
            if ($p->promedio >= 60) {
                $p->estado_admision = 'APROBADO_PENDIENTE';
            } else {
                $p->estado_admision = 'REPROBADO';
            }
            $p->carrera_admitida = null;
            $p->save();
        }

        // 2. Asignar cupos a los aprobados ordenados por mejor promedio
        $aprobados = \App\Models\Postulacion::where('estado_admision', 'APROBADO_PENDIENTE')
                        ->orderBy('promedio', 'desc')
                        ->get();
                        
        // Traer cupos disponibles por carrera (asumiendo que en 'ofrece' se declaran los cupos)
        $ofrece = DB::table('ofrece')->get();
        $cupos_disponibles = [];
        foreach ($ofrece as $o) {
            // Guardamos los cupos por semestre y carrera. 
            // Para simplificar, priorizamos la carrera independientemente del semestre, 
            // pero si hay múltiples semestres, usamos el que coincida con el postulante o el activo.
            $cupos_disponibles[$o->codigocarre] = $o->cupo;
        }

        foreach ($aprobados as $p) {
            // Traer las carreras a las que se inscribió (opcion 1 y opcion 2)
            $inscripciones = DB::table('inscribe')
                                ->where('codpost', $p->codpost)
                                ->orderBy('opcion', 'asc')
                                ->get();
            
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

        return redirect()->back()->with('success', 'Los promedios y cupos de admisión fueron calculados exitosamente.');
    }

    public function notificar(Request $request)
    {
        $postulaciones = \App\Models\Postulacion::whereNotNull('estado_admision')->get();
        $count = 0;
        
        foreach ($postulaciones as $p) {
            $usuario = \App\Models\Usuario::where('ci', $p->ciusuario)->first();
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
