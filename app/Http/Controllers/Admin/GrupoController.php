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
        $grupos = Grupo::withCount('postulantes')->get();
        
        $totalVerificados = Postulante::where('estado_admision', 'POSTULANTE_ACTIVO')->count();
        $totalAsignados = Postulante::whereNotNull('grupo_id')->count();
        $totalGrupos = Grupo::count();

        return view('admin.grupos.index', compact('grupos', 'totalVerificados', 'totalAsignados', 'totalGrupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:grupos',
            'capacidad' => 'required|integer|min:1',
        ]);

        Grupo::create($request->all());

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo creado exitosamente.');
    }

    public function autoAssign()
    {
        DB::beginTransaction();

        try {
            // Postulantes activos que aún no tienen grupo
            $postulantes = Postulante::where('estado_admision', 'POSTULANTE_ACTIVO')
                                     ->whereNull('grupo_id')
                                     ->get();

            if ($postulantes->isEmpty()) {
                return redirect()->route('admin.grupos.index')->with('info', 'No hay postulantes pendientes por asignar.');
            }

            $totalPostulantes = $postulantes->count();
            $maxPorGrupo = 70;

            // Calcular cuántos grupos se necesitan
            $cantidadGruposNecesarios = (int) ceil($totalPostulantes / $maxPorGrupo);

            // Crear los grupos necesarios
            $gruposCreados = [];
            $ultimoGrupo = Grupo::count();
            
            for ($i = 1; $i <= $cantidadGruposNecesarios; $i++) {
                $letra = chr(64 + $ultimoGrupo + $i); // A, B, C, etc.
                $gruposCreados[] = Grupo::create([
                    'nombre' => 'Grupo ' . $letra,
                    'capacidad' => $maxPorGrupo,
                    'estado' => true
                ]);
            }

            // Distribuir equitativamente
            $postulantesPorGrupo = (int) ceil($totalPostulantes / $cantidadGruposNecesarios);
            
            $indiceGrupo = 0;
            $asignadosEnGrupoActual = 0;

            foreach ($postulantes as $postulante) {
                if ($asignadosEnGrupoActual >= $postulantesPorGrupo) {
                    $indiceGrupo++;
                    $asignadosEnGrupoActual = 0;
                }

                $grupoActual = $gruposCreados[$indiceGrupo];
                $postulante->grupo_id = $grupoActual->id;
                $postulante->save();

                $asignadosEnGrupoActual++;
            }

            DB::commit();
            return redirect()->route('admin.grupos.index')->with('success', "Se asignaron {$totalPostulantes} postulantes equitativamente en {$cantidadGruposNecesarios} nuevos grupos.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.grupos.index')->with('error', 'Ocurrió un error en la asignación: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $grupo = Grupo::with('postulantes.usuario')->findOrFail($id);
        return view('admin.grupos.show', compact('grupo'));
    }
}
