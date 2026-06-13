<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    // Muestra la vista con la lista de materias configuradas
    public function index()
    {
        $materias = Materia::orderBy('id')->get()->map(function ($materia) {
            $materia->estado = $materia->estado ?? 'HABILITADO';
            return $materia;
        });

        return view('examenes.configuracion', compact('materias'));
    }

    public function update(Request $request)
    {
        $action = $request->input('action', 'update_weights');

        if ($action === 'create') {
            $data = $request->validate([
                'nombre' => 'required|string|max:100|unique:materia,nombre',
                'estado' => 'required|in:HABILITADO,INHABILITADO',
            ]);

            Materia::create([
                'nombre' => $data['nombre'],
                'estado' => $data['estado'],
            ]);

            return redirect()->route('examenes.puntos')->with('success', 'Materia creada correctamente.');
        }

        $data = $request->validate([
            'estado' => 'required|array',
            'estado.*' => 'required|in:HABILITADO,INHABILITADO',
        ]);

        foreach ($data['estado'] as $id => $estado) {
            Materia::where('id', $id)->update(['estado' => $estado]);
        }

        return redirect()->route('examenes.puntos')->with('success', 'Materias actualizadas correctamente.');
    }
}
