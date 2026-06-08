<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Materia;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Usuario::where('tipo', 'D')->get();
        $materias = Materia::all();
        
        return view('admin.docentes.index', compact('docentes', 'materias'));
    }

    public function aprobar($ci)
    {
        $docente = Usuario::where('ci', $ci)->where('tipo', 'D')->firstOrFail();
        $docente->estado_aprobacion = 'APROBADO';
        $docente->save();

        return redirect()->route('admin.docentes.index')->with('success', 'Docente aprobado correctamente.');
    }

    public function rechazar($ci)
    {
        $docente = Usuario::where('ci', $ci)->where('tipo', 'D')->firstOrFail();
        $docente->estado_aprobacion = 'RECHAZADO';
        $docente->save();

        // Opcional: liberar las materias que tenía asignadas
        Materia::where('docente_ci', $ci)->update(['docente_ci' => null]);

        return redirect()->route('admin.docentes.index')->with('success', 'Docente rechazado.');
    }

    public function asignarMateria(Request $request, $ci)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,id'
        ]);

        $docente = Usuario::where('ci', $ci)->where('tipo', 'D')->firstOrFail();
        
        // Asignar la materia a este docente
        $materia = Materia::findOrFail($request->materia_id);
        $materia->docente_ci = $docente->ci;
        $materia->save();

        return redirect()->route('admin.docentes.index')->with('success', 'Materia asignada al docente correctamente.');
    }
}
