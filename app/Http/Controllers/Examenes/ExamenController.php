<?php

namespace App\Http\Controllers\Examenes;

use App\Http\Controllers\Controller;
use App\Models\Postulante;
use App\Models\Calificacion;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    private $materias = ['Computación', 'Matemáticas', 'Inglés', 'Física'];

    public function index(Request $request)
    {
        $query = Postulante::with(['usuario', 'calificaciones' => function($q) {
            // we can load them or handle below
        }]);
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('usuario', function($q) use ($search) {
                $q->where('nombre', 'ilike', "%{$search}%")
                  ->orWhere('apellidopat', 'ilike', "%{$search}%")
                  ->orWhere('ci', 'ilike', "%{$search}%");
            });
        }
        
        $postulantes = $query->paginate(10);
        return view('examenes.index', compact('postulantes'));
    }

    public function edit($ci)
    {
        $postulante = Postulante::with('usuario')->findOrFail($ci);
        
        // Ensure all 4 subjects exist for this postulante
        foreach ($this->materias as $materia) {
            Calificacion::firstOrCreate([
                'ciusuario' => $ci,
                'materia' => $materia
            ]);
        }

        $calificaciones = Calificacion::where('ciusuario', $ci)->get();

        return view('examenes.edit', compact('postulante', 'calificaciones'));
    }

    public function update(Request $request, $ci)
    {
        $postulante = Postulante::findOrFail($ci);
        
        $notas = $request->input('notas', []); // format: notas[id][nota1] = 50
        
        foreach ($notas as $id => $data) {
            $calificacion = Calificacion::where('ciusuario', $ci)->findOrFail($id);
            
            $n1 = isset($data['nota1']) ? (int)$data['nota1'] : 0;
            $n2 = isset($data['nota2']) ? (int)$data['nota2'] : 0;
            $n3 = isset($data['nota3']) ? (int)$data['nota3'] : 0;
            
            // Limit between 0 and 100
            $n1 = max(0, min(100, $n1));
            $n2 = max(0, min(100, $n2));
            $n3 = max(0, min(100, $n3));

            // Calculate Promedio
            $promedio = round(($n1 + $n2 + $n3) / 3, 2);
            
            // Calculate Estado
            $estado = ($promedio >= 60) ? 'APROBADO' : 'REPROBADO';

            $calificacion->update([
                'nota1' => $n1,
                'nota2' => $n2,
                'nota3' => $n3,
                'promedio' => $promedio,
                'estado' => $estado
            ]);
        }

        return redirect()->route('examenes.index')->with('success', 'Notas registradas y promedios calculados correctamente.');
    }
}
