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
        $materias = Materia::orderBy('id')->get();
        return view('examenes.configuracion', compact('materias'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'puntos' => 'required|array',
            'puntos.*' => 'required|integer|min:0|max:100',
        ]);

        $suma = array_sum($data['puntos']);

        // Validamos que la suma total de puntos sea exactamente 100
        if ($suma !== 100) {
            return back()->with('error', 'La suma de los puntos de todas las materias debe ser exactamente 100. La suma actual es ' . $suma . '.');
        }

        // Actualizamos los puntos de cada materia en la base
        foreach ($data['puntos'] as $id => $puntos) {
            Materia::where('id', $id)->update(['puntos' => $puntos]);
        }

        return back()->with('success', 'Asignación de puntos actualizada correctamente.');
    }
}
