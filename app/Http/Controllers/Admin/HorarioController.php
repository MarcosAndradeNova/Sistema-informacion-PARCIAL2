<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Aula;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::with(['grupodocente.grupo', 'grupodocente.docente', 'grupodocente.materia'])
            ->orderBy('nroaula')
            ->orderBy('dia')
            ->orderBy('iniciohorario')
            ->paginate(15);
            
        $aulas = DB::table('aula')->get();
        
        return view('admin.horarios.index', compact('horarios', 'aulas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dia' => 'required|string',
            'iniciohorario' => 'required',
            'finhorario' => 'required',
            'nroaula' => 'required'
        ]);

        // Verificar choque de horarios en la misma aula
        // Dos horarios chocan si sus intervalos de tiempo se solapan
        // H1 solapa con H2 si (H1.inicio < H2.fin) AND (H1.fin > H2.inicio)
        $choque = Horario::where('nroaula', $request->nroaula)
            ->where('dia', $request->dia)
            ->where('iniciohorario', '<', $request->finhorario)
            ->where('finhorario', '>', $request->iniciohorario)
            ->exists();

        if ($choque) {
            return redirect()->back()->with('error', 'El horario choca con otro registro existente en esa misma aula y día.');
        }

        Horario::create([
            'dia' => $request->dia,
            'iniciohorario' => $request->iniciohorario,
            'finhorario' => $request->finhorario,
            'nroaula' => $request->nroaula,
        ]);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario de aula creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dia' => 'required|string',
            'iniciohorario' => 'required',
            'finhorario' => 'required',
            'nroaula' => 'required'
        ]);

        $choque = Horario::where('id', '!=', $id)
            ->where('nroaula', $request->nroaula)
            ->where('dia', $request->dia)
            ->where('iniciohorario', '<', $request->finhorario)
            ->where('finhorario', '>', $request->iniciohorario)
            ->exists();

        if ($choque) {
            return redirect()->back()->with('error', 'El horario que intentas guardar choca con otro registro existente en esa aula.');
        }

        $horario = Horario::findOrFail($id);
        $horario->update([
            'dia' => $request->dia,
            'iniciohorario' => $request->iniciohorario,
            'finhorario' => $request->finhorario,
            'nroaula' => $request->nroaula,
        ]);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        
        // Check if assigned to any group
        $hasGroup = DB::table('grupodocente')->where('idhorario', $id)->exists();
        if ($hasGroup) {
            return redirect()->back()->with('error', 'No se puede eliminar este horario porque está asignado a un grupo docente. Debes reasignar al grupo primero.');
        }
        
        $horario->delete();
        return redirect()->route('admin.horarios.index')->with('success', 'Horario eliminado correctamente.');
    }
}
