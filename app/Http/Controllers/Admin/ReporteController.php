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
        // Obtener todos los postulantes con su información y postulaciones
        $postulantes = Postulante::with(['usuario', 'postulaciones'])->get();
        
        // OPTIMIZACIÓN: Traer todas las notas de una sola vez y agruparlas por usuario en memoria (evita problema N+1)
        $allNotas = \App\Models\ResultadoExam::all()->groupBy('ciusuario');

        $estudiantesData = [];

        foreach ($postulantes as $p) {
            $grupo = 'SIN GRUPO';
            if ($p->postulaciones->count() > 0 && $p->postulaciones->first()->codgrupo) {
                $grupo = 'Grupo ' . $p->postulaciones->first()->codgrupo;
            }

            // Recuperar notas desde la colección en memoria
            $notas = $allNotas->get($p->ciusuario, collect());
            
            // Calcular un promedio general
            $promedio = $notas->count() > 0 ? $notas->avg('calificacion') : 0;
            $estado = $promedio >= 51 ? 'APROBADO' : 'REPROBADO';
            if ($notas->count() == 0) {
                $estado = 'SIN NOTAS';
            }

            $estudiantesData[] = [
                'ci' => $p->ciusuario,
                'nombre' => trim(($p->usuario->apellidopat ?? '') . ' ' . ($p->usuario->apellidomat ?? '') . ' ' . ($p->usuario->nombre ?? '')),
                'email' => $p->usuario->email ?? '',
                'grupo' => $grupo,
                'promedio' => round($promedio, 2),
                'estado' => $estado,
                'estado_docum' => $p->estadodocum
            ];
        }

        $docentes = \App\Models\Docente::with('usuario')->get();
        $docentesData = [];
        foreach ($docentes as $d) {
            $docentesData[] = [
                'ci' => $d->ciusuario,
                'nombre' => trim(($d->usuario->apellidopat ?? '') . ' ' . ($d->usuario->apellidomat ?? '') . ' ' . ($d->usuario->nombre ?? '')),
                'email' => $d->usuario->email ?? '',
                'profesion' => $d->profesion ?? 'No Especificada',
                'estado' => $d->estado ?? 'PENDIENTE'
            ];
        }

        $gruposDB = Grupo::all();
        
        // OPTIMIZACIÓN: Contar inscritos de todos los grupos de una sola vez (evita problema N+1)
        $inscritosPorGrupo = \App\Models\Postulacion::select('codgrupo', DB::raw('count(*) as total'))
            ->groupBy('codgrupo')
            ->pluck('total', 'codgrupo');

        $gruposData = [];
        foreach ($gruposDB as $g) {
            $inscritos = $inscritosPorGrupo->get($g->codigo, 0);
            $gruposData[] = [
                'codigo' => $g->codigo,
                'nombre' => $g->nombre,
                'cupo' => $g->cupo,
                'inscritos' => $inscritos
            ];
        }

        return view('admin.reportes.index', compact('estudiantesData', 'docentesData', 'gruposData'));
    }

    public function exportCsv(Request $request)
    {
        // Similar filter logic could be applied here if needed, but we'll do client-side CSV generation
    }
}
