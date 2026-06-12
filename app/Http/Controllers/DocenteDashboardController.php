<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Calificacion;

class DocenteDashboardController extends Controller
{
    private function getDocente()
    {
        return Usuario::where('email', Auth::user()->email)->first();
    }

    public function miMateria()
    {
        $docente = $this->getDocente();
        $materias = Materia::join('grupodocente', 'materia.id', '=', 'grupodocente.idmateria')
                           ->where('grupodocente.ciusuario', $docente->ci)
                           ->select('materia.*')
                           ->distinct()
                           ->get();

        return view('docente.mi_materia', compact('materias'));
    }

    public function updateMateria(Request $request, $id)
    {
        $docente = $this->getDocente();
        $materia = Materia::join('grupodocente', 'materia.id', '=', 'grupodocente.idmateria')
                          ->where('materia.id', $id)
                          ->where('grupodocente.ciusuario', $docente->ci)
                          ->select('materia.*')
                          ->firstOrFail();
        
        $request->validate([
            'temario_avance' => 'nullable|string',
            'enlaces_material' => 'nullable|string'
        ]);

        $materia->temario_avance = $request->temario_avance;
        $materia->enlaces_material = $request->enlaces_material;
        $materia->save();

        return redirect()->route('docente.mi_materia')->with('success', 'Material y temario actualizados correctamente.');
    }

    public function misGrupos(Request $request)
    {
        $search = $request->input('search');

        $docente = $this->getDocente();
        $ciDocente = $docente ? $docente->ci : null;

        if (!$ciDocente) {
            $grupos = collect();
            $gruposDocente = collect();
            return view('docente.mis_grupos', compact('grupos', 'gruposDocente', 'search'));
        }

        // Obtener los grupos con sus respectivos horarios para este docente
        $gruposDocente = \App\Models\GrupoDocente::where('ciusuario', $ciDocente)
            ->join('grupo', 'grupodocente.codigogrupo', '=', 'grupo.codigo')
            ->join('horario', 'grupodocente.idhorario', '=', 'horario.id')
            ->select('grupodocente.*', 'grupo.nombre as nombre_grupo', 'horario.dia', 'horario.iniciohorario', 'horario.finhorario', 'horario.nroaula')
            ->get();

        $misGruposCodigos = $gruposDocente->pluck('codigogrupo');

        $grupos = Grupo::whereIn('codigo', $misGruposCodigos)->with(['postulantes' => function($query) use ($search) {
            $query->where('estadodocum', 'INSCRITO')->with('usuario');
            
            if ($search) {
                $query->whereHas('usuario', function($q) use ($search) {
                    $q->where('nombre', 'ILIKE', "%{$search}%")
                      ->orWhere('apellidopat', 'ILIKE', "%{$search}%")
                      ->orWhere('apellidomat', 'ILIKE', "%{$search}%");
                });
            }
        }])->get();

        return view('docente.mis_grupos', compact('grupos', 'gruposDocente', 'search'));
    }

    public function updateWhatsapp(Request $request)
    {
        $request->validate([
            'grupodocente_id' => 'required', // codigogrupo
            'whatsapp_link' => 'nullable|url'
        ]);

        $docente = $this->getDocente();
        $ciDocente = $docente ? $docente->ci : null;
        
        if ($ciDocente) {
            \App\Models\GrupoDocente::where('ciusuario', $ciDocente)
                ->where('codigogrupo', $request->grupodocente_id)
                ->update(['whatsapp_link' => $request->whatsapp_link]);
        }

        return redirect()->route('docente.mis_grupos')->with('success', 'Enlace de WhatsApp actualizado correctamente.');
    }

    public function cronograma()
    {
        return view('docente.cronograma');
    }

    public function calificaciones(Request $request)
    {
        $docente = $this->getDocente();
        $materias = Materia::join('grupodocente', 'materia.id', '=', 'grupodocente.idmateria')
                           ->where('grupodocente.ciusuario', $docente->ci)
                           ->select('materia.*')
                           ->distinct()
                           ->get();
        
        $materiaSeleccionada = null;
        $estudiantes = collect();
        $calificacionesMap = [];
        $codigosGrupos = collect();

        if ($request->has('materia_id')) {
            $materiaSeleccionada = Materia::join('grupodocente', 'materia.id', '=', 'grupodocente.idmateria')
                                          ->where('materia.id', $request->materia_id)
                                          ->where('grupodocente.ciusuario', $docente->ci)
                                          ->select('materia.*')
                                          ->first();
                                          
            if ($materiaSeleccionada) {
                // Obtener los códigos de los grupos que el docente enseña para esta materia
                $codigosGrupos = \App\Models\GrupoDocente::where('ciusuario', $docente->ci)
                    ->where('idmateria', $materiaSeleccionada->id)
                    ->pluck('codigogrupo');

                // Obtener estudiantes activos inscritos en esos grupos
                $estudiantes = Postulante::where('estadodocum', 'INSCRITO')
                                         ->whereHas('grupos', function($query) use ($codigosGrupos) {
                                             $query->whereIn('codigo', $codigosGrupos);
                                         })
                                         ->with(['usuario', 'postulaciones'])
                                         ->get();
                
                // Obtener calificaciones existentes para esta materia
                $calificaciones = \App\Models\ResultadoExam::where('idmateria', $materiaSeleccionada->id)->get();
                foreach ($calificaciones as $calif) {
                    $calificacionesMap[$calif->ciusuario][$calif->nroexamen] = $calif;
                }
            }
        }

        $examenes = \Illuminate\Support\Facades\DB::table('examen')->get();
        if ($examenes->isEmpty()) {
            \Illuminate\Support\Facades\DB::table('examen')->insert([
                ['nro' => 1, 'descripcion' => 'Primer Parcial', 'fecha' => now()],
                ['nro' => 2, 'descripcion' => 'Segundo Parcial', 'fecha' => now()],
                ['nro' => 3, 'descripcion' => 'Examen Final', 'fecha' => now()],
            ]);
            $examenes = \Illuminate\Support\Facades\DB::table('examen')->get();
        }

        return view('docente.calificaciones', compact('materias', 'materiaSeleccionada', 'estudiantes', 'calificacionesMap', 'examenes', 'codigosGrupos'));
    }

    public function updateCalificaciones(Request $request)
    {
        $request->validate([
            'materia_id' => 'required|exists:materia,id',
            'notas' => 'required|array'
        ]);

        $docente = $this->getDocente();
        $materia = Materia::join('grupodocente', 'materia.id', '=', 'grupodocente.idmateria')
                          ->where('materia.id', $request->materia_id)
                          ->where('grupodocente.ciusuario', $docente->ci)
                          ->select('materia.*')
                          ->firstOrFail();

        $examenes = \Illuminate\Support\Facades\DB::table('examen')->get();

        foreach ($request->notas as $ciusuario => $datosEstudiante) {
            $codpost = $datosEstudiante['codpost'] ?? null;
            $codigogrupo = $datosEstudiante['codigogrupo'] ?? null;

            if (!$codpost || !$codigogrupo) continue;
            
            $codigogrupoEntero = intval(preg_replace('/[^0-9]/', '', $codigogrupo));

            foreach ($examenes as $examen) {
                $nro = $examen->nro;
                if (isset($datosEstudiante['nota' . $nro]) && $datosEstudiante['nota' . $nro] !== '') {
                    $calificacion = floatval($datosEstudiante['nota' . $nro]);

                    \App\Models\ResultadoExam::updateOrCreate(
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

        return redirect()->route('docente.calificaciones', ['materia_id' => $materia->id])
                         ->with('success', 'Calificaciones guardadas exitosamente.');
    }
}
