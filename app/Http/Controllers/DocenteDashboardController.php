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

    public function misMaterias()
    {
        $docente = $this->getDocente();
        $materias = Materia::where('docente_ci', $docente->ci)->get();

        return view('docente.mis_materias', compact('materias'));
    }

    public function updateMateria(Request $request, $id)
    {
        $docente = $this->getDocente();
        $materia = Materia::where('id', $id)->where('docente_ci', $docente->ci)->firstOrFail();
        
        $request->validate([
            'temario_avance' => 'nullable|string',
            'enlaces_material' => 'nullable|string'
        ]);

        $materia->temario_avance = $request->temario_avance;
        $materia->enlaces_material = $request->enlaces_material;
        $materia->save();

        return redirect()->route('docente.mis_materias')->with('success', 'Material y temario actualizados correctamente.');
    }

    public function misEstudiantes(Request $request)
    {
        $search = $request->input('search');

        $grupos = Grupo::with(['postulantes' => function($query) use ($search) {
            $query->where('estado_admision', 'POSTULANTE_ACTIVO')->with('usuario');
            
            if ($search) {
                $query->whereHas('usuario', function($q) use ($search) {
                    $q->where('nombre', 'ILIKE', "%{$search}%")
                      ->orWhere('apellido_pat', 'ILIKE', "%{$search}%")
                      ->orWhere('apellido_mat', 'ILIKE', "%{$search}%");
                });
            }
        }])->get();

        return view('docente.mis_estudiantes', compact('grupos', 'search'));
    }

    public function cronograma()
    {
        return view('docente.cronograma');
    }

    public function calificaciones(Request $request)
    {
        $docente = $this->getDocente();
        $materias = Materia::where('docente_ci', $docente->ci)->get();
        
        $materiaSeleccionada = null;
        $estudiantes = collect();
        $calificacionesMap = [];

        if ($request->has('materia_id')) {
            $materiaSeleccionada = Materia::where('id', $request->materia_id)
                                          ->where('docente_ci', $docente->ci)
                                          ->first();
                                          
            if ($materiaSeleccionada) {
                // Obtener todos los estudiantes activos
                $estudiantes = Postulante::where('estado_admision', 'POSTULANTE_ACTIVO')
                                         ->with('usuario', 'grupo')
                                         ->get();
                
                // Obtener calificaciones existentes para esta materia
                $calificaciones = Calificacion::where('materia', $materiaSeleccionada->nombre)->get();
                foreach ($calificaciones as $calif) {
                    $calificacionesMap[$calif->ci_usuario] = $calif;
                }
            }
        }

        return view('docente.calificaciones', compact('materias', 'materiaSeleccionada', 'estudiantes', 'calificacionesMap'));
    }

    public function updateCalificaciones(Request $request)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'notas' => 'required|array'
        ]);

        $docente = $this->getDocente();
        $materia = Materia::where('id', $request->materia_id)->where('docente_ci', $docente->ci)->firstOrFail();

        foreach ($request->notas as $ci_usuario => $datosNota) {
            // Check if any note is filled
            if (isset($datosNota['nota1']) || isset($datosNota['nota2']) || isset($datosNota['nota3'])) {
                
                $n1 = isset($datosNota['nota1']) && $datosNota['nota1'] !== '' ? floatval($datosNota['nota1']) : 0;
                $n2 = isset($datosNota['nota2']) && $datosNota['nota2'] !== '' ? floatval($datosNota['nota2']) : 0;
                $n3 = isset($datosNota['nota3']) && $datosNota['nota3'] !== '' ? floatval($datosNota['nota3']) : 0;
                
                // Promedio simple (puedes ajustarlo según tu lógica)
                $promedio = ($n1 + $n2 + $n3) / 3;
                $estado = $promedio >= 51 ? 'Aprobado' : 'Reprobado';

                Calificacion::updateOrCreate(
                    [
                        'ci_usuario' => $ci_usuario,
                        'materia' => $materia->nombre
                    ],
                    [
                        'nota1' => $n1,
                        'nota2' => $n2,
                        'nota3' => $n3,
                        'promedio' => number_format($promedio, 2),
                        'estado' => $estado
                    ]
                );
            }
        }

        return redirect()->route('docente.calificaciones', ['materia_id' => $materia->id])
                         ->with('success', 'Calificaciones guardadas exitosamente.');
    }
}
