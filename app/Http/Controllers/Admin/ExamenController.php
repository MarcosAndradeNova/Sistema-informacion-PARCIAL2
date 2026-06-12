<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Postulante;
use App\Models\ResultadoExam;
use Illuminate\Support\Facades\DB;

class ExamenController extends Controller
{
    public function index(Request $request)
    {
        $materias = Materia::all();
        $grupos = Grupo::all();

        $materiaSeleccionada = null;
        $grupoSeleccionado = null;
        $estudiantes = collect();
        $calificacionesMap = [];

        if ($request->has('materia_id') && $request->has('grupo_id')) {
            $materiaSeleccionada = Materia::find($request->materia_id);
            $grupoSeleccionado = Grupo::where('codigo', $request->grupo_id)->first();

            if ($materiaSeleccionada && $grupoSeleccionado) {
                // Obtener estudiantes en este grupo
                $estudiantes = Postulante::where('estadodocum', 'INSCRITO')
                    ->whereHas('grupos', function($query) use ($grupoSeleccionado) {
                        $query->where('codigo', $grupoSeleccionado->codigo);
                    })
                    ->with(['usuario', 'postulaciones'])
                    ->get();
                
                // Convertir el código de grupo a entero (ej: "G1" -> 1, "11" -> 11) para evitar el error de postgres int4
                $codigoEntero = intval(preg_replace('/[^0-9]/', '', $grupoSeleccionado->codigo));

                $calificaciones = ResultadoExam::where('idmateria', $materiaSeleccionada->id)
                                             ->where('codigogrupo', $codigoEntero)
                                             ->get();
                foreach ($calificaciones as $calif) {
                    $calificacionesMap[$calif->ciusuario][$calif->nroexamen] = $calif;
                }
            }
        }

        $examenes = DB::table('examen')->get();
        if ($examenes->isEmpty()) {
            DB::table('examen')->insert([
                ['nro' => 1, 'descripcion' => 'Primer Parcial', 'fecha' => now()],
                ['nro' => 2, 'descripcion' => 'Segundo Parcial', 'fecha' => now()],
                ['nro' => 3, 'descripcion' => 'Examen Final', 'fecha' => now()],
            ]);
            $examenes = DB::table('examen')->get();
        }

        return view('admin.examenes.index', compact('materias', 'grupos', 'materiaSeleccionada', 'grupoSeleccionado', 'estudiantes', 'calificacionesMap', 'examenes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'materia_id' => 'required|exists:materia,id',
            'grupo_id' => 'required|exists:grupo,codigo',
            'notas' => 'required|array'
        ]);

        $materia = Materia::findOrFail($request->materia_id);
        $examenes = DB::table('examen')->get();

        foreach ($request->notas as $ciusuario => $datosEstudiante) {
            $codpost = $datosEstudiante['codpost'] ?? null;
            // Extraer solo la parte numérica para la llave foránea
            $codigogrupoEntero = intval(preg_replace('/[^0-9]/', '', $request->grupo_id));

            if (!$codpost) continue;

            foreach ($examenes as $examen) {
                $nro = $examen->nro;
                if (isset($datosEstudiante['nota' . $nro]) && $datosEstudiante['nota' . $nro] !== '') {
                    $calificacion = floatval($datosEstudiante['nota' . $nro]);

                    ResultadoExam::updateOrCreate(
                        [
                            'nroexamen' => $nro,
                            'ciusuario' => $ciusuario,
                            'idmateria' => $materia->id
                        ],
                        [
                            'codigogrupo' => $codigogrupoEntero,
                            'codpost' => $codpost,
                            'calificacion' => $calificacion
                        ]
                    );
                }
            }
        }

        // Registrar en bitácora
        $usuarioDb = \App\Models\Usuario::where('email', \Illuminate\Support\Facades\Auth::user()->email)->first();
        if ($usuarioDb) {
            \App\Models\Bitacora::create([
                'accion' => 'Modificación de notas: Materia ' . $materia->nombre . ' (Grupo ' . $request->grupo_id . ')',
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'ip' => $request->ip(),
                'ciusuario' => $usuarioDb->ci
            ]);
        }

        return redirect()->route('admin.examenes.index', ['materia_id' => $request->materia_id, 'grupo_id' => $request->grupo_id])
                         ->with('success', 'Calificaciones actualizadas y auditadas exitosamente.');
    }
}
