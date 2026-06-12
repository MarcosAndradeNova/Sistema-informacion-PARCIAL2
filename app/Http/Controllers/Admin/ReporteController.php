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
        // Obtener todos los postulantes con su información, grupo y notas
        $postulantes = Postulante::with(['usuario', 'postulaciones.grupo'])->get();
        
        $estudiantesData = [];

        foreach ($postulantes as $p) {
            $grupo = 'SIN GRUPO';
            if ($p->postulaciones->count() > 0 && $p->postulaciones->first()->grupo) {
                $grupo = 'Grupo ' . $p->postulaciones->first()->codgrupo;
            }

            // Obtener notas
            $notas = \App\Models\ResultadoExam::where('ciusuario', $p->ciusuario)->get();
            // Calcular un promedio general muy simplificado para el reporte (promedio de todas sus notas)
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

        return view('admin.reportes.index', compact('estudiantesData'));
    }

    public function exportCsv(Request $request)
    {
        // Similar filter logic could be applied here if needed, but we'll do client-side CSV generation
    }
}
