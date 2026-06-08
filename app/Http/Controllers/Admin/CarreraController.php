<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    // Obtiene y muestra todas las carreras para el administrador
    public function index()
    {
        $carreras = Carrera::orderBy('codigo')->get();
        return view('admin.carreras.index', compact('carreras'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'carreras' => 'required|array',
            'carreras.*.cupo' => 'required|integer|min:0',
            'carreras.*.semestre' => 'required|string|max:20',
        ]);

        // Itera y actualiza cupos y semestres de cada carrera
        foreach ($data['carreras'] as $codigo => $carreraData) {
            Carrera::where('codigo', $codigo)->update([
                'cupo' => $carreraData['cupo'],
                'semestre' => $carreraData['semestre'],
            ]);
        }

        return back()->with('success', 'Cupos y semestres actualizados correctamente.');
    }
}
