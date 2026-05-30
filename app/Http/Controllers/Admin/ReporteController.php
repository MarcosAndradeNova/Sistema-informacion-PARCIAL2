<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Postulante;
use App\Models\Grupo;
use App\Models\Calificacion;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // 1. Lista general de postulantes
        $postulantesGenerales = Postulante::with('usuario', 'grupo')->get();

        // Obtener todos los postulantes con sus calificaciones
        $postulantes = Postulante::with(['usuario', 'calificaciones', 'grupo'])->get();

        $aprobados = collect();
        $reprobados = collect();

        foreach ($postulantes as $postulante) {
            // Estudiante aprobado si en 4 materias pasó con nota mínima 60
            $materiasAprobadas = $postulante->calificaciones->filter(function($cal) {
                return $cal->promedio >= 60;
            })->count();

            $postulante->materias_aprobadas_count = $materiasAprobadas;

            if ($materiasAprobadas >= 4) {
                $aprobados->push($postulante);
            } else {
                $reprobados->push($postulante);
            }
        }

        // 2. Postulantes aprobados
        $postulantesAprobados = $aprobados;

        // 3. Postulantes reprobados
        $postulantesReprobados = $reprobados;

        // 4. Promedios generales (por postulante)
        $promediosGenerales = $postulantes->map(function ($p) {
            $promedio = $p->calificaciones->avg('promedio') ?? 0;
            $p->promedio_general = round($promedio, 2);
            return $p;
        })->sortByDesc('promedio_general');

        // 5. Cantidad de grupos habilitados
        $grupos = Grupo::withCount('postulantes')->get();
        $cantidadGrupos = $grupos->count();

        // 6. Estadísticas por materia
        $estadisticasMateria = DB::table('calificaciones_postulante')
            ->select('materia', 
                DB::raw('count(*) as total_evaluados'),
                DB::raw('avg(promedio) as promedio_general'),
                DB::raw('sum(case when promedio >= 60 then 1 else 0 end) as total_aprobados'),
                DB::raw('sum(case when promedio < 60 then 1 else 0 end) as total_reprobados')
            )
            ->groupBy('materia')
            ->get();

        // 7. Docentes por grupos
        $docentesPorGrupo = Grupo::with('docentes')->whereHas('docentes')->get();

        // 8. Grupos con mayor cantidad de aprobados
        // Se calcula iterando sobre los aprobados y agrupándolos por su grupo_id
        $aprobadosPorGrupo = $aprobados->groupBy('grupo_id')->map(function ($grupoAprobados, $grupoId) {
            return $grupoAprobados->count();
        });

        $gruposRanking = $grupos->map(function ($grupo) use ($aprobadosPorGrupo) {
            $grupo->cantidad_aprobados = $aprobadosPorGrupo->get($grupo->id, 0);
            return $grupo;
        })->sortByDesc('cantidad_aprobados')->values();

        return view('admin.reportes.index', compact(
            'postulantesGenerales',
            'postulantesAprobados',
            'postulantesReprobados',
            'promediosGenerales',
            'cantidadGrupos',
            'grupos',
            'estadisticasMateria',
            'docentesPorGrupo',
            'gruposRanking'
        ));
    }
}
