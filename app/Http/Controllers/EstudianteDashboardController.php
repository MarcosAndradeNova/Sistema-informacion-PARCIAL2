<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Calificacion;

class EstudianteDashboardController extends Controller
{
    private function getPostulante()
    {
        $usuario = Usuario::where('email', Auth::user()->email)->first();
        if (!$usuario) return null;
        return Postulante::where('ci_usuario', $usuario->ci)->first();
    }

    public function miGrupo()
    {
        $postulante = $this->getPostulante();
        if (!$postulante || $postulante->estado_admision !== 'POSTULANTE_ACTIVO') {
            return redirect()->route('dashboard')->with('error', 'Aún no eres un postulante activo.');
        }

        // Auto-reparación: si el estudiante es activo pero no tiene grupo (ej. cuenta antigua), se le asigna uno ahora.
        if (!$postulante->grupo_id) {
            $grupos = \App\Models\Grupo::where('estado', 1)->withCount('postulantes')->get();
            $grupoDisponible = $grupos->first(function ($g) {
                return $g->postulantes_count < $g->capacidad;
            });
            
            if (!$grupoDisponible) {
                // Crear nuevo grupo si no hay ninguno o están todos llenos
                $ultimoGrupo = \App\Models\Grupo::count();
                $letra = chr(65 + $ultimoGrupo); // A, B, C...
                $grupoDisponible = \App\Models\Grupo::create([
                    'nombre' => 'Grupo ' . $letra,
                    'capacidad' => 70,
                    'estado' => true
                ]);
            }
            
            $postulante->grupo_id = $grupoDisponible->id;
            $postulante->save();
        }

        $grupo = Grupo::findOrFail($postulante->grupo_id);
        
        // Buscar compañeros del mismo grupo (excluyendo al postulante actual)
        $companeros = Postulante::with('usuario')
            ->where('grupo_id', $grupo->id)
            ->where('ci_usuario', '!=', $postulante->ci_usuario)
            ->get();

        return view('estudiante.mi_grupo', compact('grupo', 'companeros'));
    }

    public function misMaterias()
    {
        $postulante = $this->getPostulante();
        if (!$postulante || $postulante->estado_admision !== 'POSTULANTE_ACTIVO') {
            return redirect()->route('dashboard')->with('error', 'Debes ser un postulante activo para ver tus materias.');
        }

        $materias = Materia::all();
        
        return view('estudiante.mis_materias', compact('materias'));
    }

    public function misExamenes()
    {
        $postulante = $this->getPostulante();
        if (!$postulante || $postulante->estado_admision !== 'POSTULANTE_ACTIVO') {
            return redirect()->route('dashboard')->with('error', 'Debes ser un postulante activo para ver tus exámenes.');
        }

        // Se obtienen las calificaciones (notas de los exámenes) de este estudiante
        $calificaciones = Calificacion::where('ci_usuario', $postulante->ci_usuario)->get();

        return view('estudiante.mis_examenes', compact('calificaciones'));
    }
}
