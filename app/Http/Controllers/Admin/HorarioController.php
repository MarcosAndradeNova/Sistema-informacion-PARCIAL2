<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Aula;
use Illuminate\Support\Facades\DB;

/**
 * Controlador encargado de gestionar la creación, edición y eliminación 
 * de los horarios y asignación de aulas físicas.
 */
class HorarioController extends Controller
{
    /**
     * Muestra la vista principal con la tabla de horarios registrados.
     * Utiliza Eager Loading (with) para optimizar la carga de relaciones.
     */
    public function index()
    {
        // Recupera los horarios paginados incluyendo la información del grupo y docente asignado.
        $horarios = Horario::with(['grupodocente.grupo', 'grupodocente.docente', 'grupodocente.materia'])
            ->orderBy('nroaula')
            ->orderBy('dia')
            ->orderBy('iniciohorario')
            ->paginate(15);
            
        // Lista todas las aulas disponibles para los menús desplegables (modales).
        $aulas = DB::table('aula')->get();
        
        return view('admin.horarios.index', compact('horarios', 'aulas'));
    }

    /**
     * Almacena un nuevo bloque de horario en la base de datos, 
     * validando estrictamente que no exista choque de horas en la misma aula.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos entrantes del formulario
        $request->validate([
            'dia' => 'required|string',
            'iniciohorario' => 'required',
            'finhorario' => 'required',
            'nroaula' => 'required'
        ]);

        // 2. Algoritmo de validación de choque de horarios.
        // Dos horarios chocan si sus intervalos de tiempo se solapan
        // H1 solapa con H2 si (H1.inicio < H2.fin) AND (H1.fin > H2.inicio)
        $choque = Horario::where('nroaula', $request->nroaula)
            ->where('dia', $request->dia)
            ->where('iniciohorario', '<', $request->finhorario)
            ->where('finhorario', '>', $request->iniciohorario)
            ->exists();

        if ($choque) {
            // Retorna al usuario con un error si detecta solapamiento
            return redirect()->back()->with('error', 'El horario choca con otro registro existente en esa misma aula y día.');
        }

        // 3. Creación del registro si pasa la validación
        Horario::create([
            'dia' => $request->dia,
            'iniciohorario' => $request->iniciohorario,
            'finhorario' => $request->finhorario,
            'nroaula' => $request->nroaula,
        ]);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario de aula creado exitosamente.');
    }

    /**
     * Actualiza un horario existente, verificando que los nuevos datos 
     * no generen conflictos con otros horarios (ignorando a sí mismo).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dia' => 'required|string',
            'iniciohorario' => 'required',
            'finhorario' => 'required',
            'nroaula' => 'required'
        ]);

        // Verifica colisiones excluyendo el ID del horario que estamos editando
        $choque = Horario::where('id', '!=', $id)
            ->where('nroaula', $request->nroaula)
            ->where('dia', $request->dia)
            ->where('iniciohorario', '<', $request->finhorario)
            ->where('finhorario', '>', $request->iniciohorario)
            ->exists();

        if ($choque) {
            return redirect()->back()->with('error', 'El horario que intentas guardar choca con otro registro existente en esa aula.');
        }

        // Actualiza los datos en la base de datos
        $horario = Horario::findOrFail($id);
        $horario->update([
            'dia' => $request->dia,
            'iniciohorario' => $request->iniciohorario,
            'finhorario' => $request->finhorario,
            'nroaula' => $request->nroaula,
        ]);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario actualizado exitosamente.');
    }

    /**
     * Elimina un horario siempre y cuando no tenga un grupo docente dependiente.
     */
    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        
        // Regla de Integridad: Evitar borrar horarios que ya estén asignados a una clase activa
        $hasGroup = DB::table('grupodocente')->where('idhorario', $id)->exists();
        if ($hasGroup) {
            return redirect()->back()->with('error', 'No se puede eliminar este horario porque está asignado a un grupo docente. Debes reasignar al grupo primero.');
        }
        
        $horario->delete();
        return redirect()->route('admin.horarios.index')->with('success', 'Horario eliminado correctamente.');
    }
}

