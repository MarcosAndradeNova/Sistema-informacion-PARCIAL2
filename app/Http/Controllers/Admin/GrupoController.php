<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Grupo;
use App\Models\Postulante;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::all();
        foreach($grupos as $g) {
            $g->postulantes_count = \App\Models\Postulacion::where('codgrupo', $g->codigo)->count();
        }
        
        $totalVerificados = Postulante::where('estadodocum', 'INSCRITO')->count();
        $totalAsignados = \App\Models\Postulacion::whereNotNull('codgrupo')->count();
        $totalGrupos = Grupo::count();

        return view('admin.grupos.index', compact('grupos', 'totalVerificados', 'totalAsignados', 'totalGrupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
        ]);

        $codigo = 'G' . (Grupo::count() + 1);
        Grupo::create([
            'codigo' => $codigo,
            'nombre' => $request->nombre,
            'cupo' => $request->capacidad,
            'idturno' => 1
        ]);

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo creado exitosamente.');
    }

    public function edit($codigo)
    {
        $grupo = Grupo::where('codigo', $codigo)->firstOrFail();
        return view('admin.grupos.edit', compact('grupo'));
    }

    public function update(Request $request, $codigo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
        ]);

        $grupo = Grupo::where('codigo', $codigo)->firstOrFail();
        $grupo->update([
            'nombre' => $request->nombre,
            'cupo' => $request->capacidad,
        ]);

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo actualizado exitosamente.');
    }

    public function autoAssign()
    {
        DB::beginTransaction();

        try {
            // Postulantes activos que aún no tienen grupo (donde su postulacion no tiene codgrupo)
            $postulantes = Postulante::where('estadodocum', 'INSCRITO')
                                     ->whereHas('postulaciones', function($q) {
                                         $q->whereNull('codgrupo');
                                     })
                                     ->get();

            if ($postulantes->isEmpty()) {
                return redirect()->route('admin.grupos.index')->with('info', 'No hay postulantes pendientes por asignar.');
            }

            foreach ($postulantes as $postulante) {
                // Find a group with available capacity
                $grupos = Grupo::all();
                $grupoDisponible = null;
                
                foreach ($grupos as $g) {
                    $inscritos = \App\Models\Postulacion::where('codgrupo', $g->codigo)->count();
                    if ($inscritos < $g->cupo) {
                        $grupoDisponible = $g;
                        break;
                    }
                }

                // Si no hay grupos con espacio, crear uno nuevo
                if (!$grupoDisponible) {
                    $countGrupos = Grupo::count();
                    $nuevoCodigo = 'G' . ($countGrupos + 1);
                    $grupoDisponible = Grupo::create([
                        'codigo' => $nuevoCodigo,
                        'nombre' => 'Grupo ' . $nuevoCodigo,
                        'cupo' => 70,
                        'idturno' => 1
                    ]);
                }

                $postulacion = \App\Models\Postulacion::where('ciusuario', $postulante->ciusuario)->first();
                if ($postulacion) {
                    $postulacion->codgrupo = $grupoDisponible->codigo;
                    $postulacion->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.grupos.index')->with('success', "Se asignaron exitosamente a los nuevos grupos.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.grupos.index')->with('error', 'Ocurrió un error en la asignación: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // $id is the 'codigo' now
        $grupo = Grupo::where('codigo', $id)->firstOrFail();
        $postulaciones = \App\Models\Postulacion::with('postulante.usuario')->where('codgrupo', $grupo->codigo)->get();
        return view('admin.grupos.show', compact('grupo', 'postulaciones'));
    }
}
