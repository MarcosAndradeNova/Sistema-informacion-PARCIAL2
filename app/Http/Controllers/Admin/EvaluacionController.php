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
}
