<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Postulante;
use App\Models\ResultadoExam;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;

class ExamenController extends Controller
{
    public function index(Request $request)
    {
        $materias = Materia::all();
        $grupos = Grupo::all();

        $materiaSeleccionada = null;
        $grupoSeleccionado = null;
        $estudiantes = collect();
        $calificacionesMap = [];

        $grupoDocenteInfo = null;

        if ($request->has('materia_id') && $request->has('grupo_id')) {
            $materiaSeleccionada = Materia::find($request->materia_id);
            $grupoSeleccionado = Grupo::where('codigo', $request->grupo_id)->first();

            if ($materiaSeleccionada && $grupoSeleccionado) {
                $grupoDocenteInfo = \App\Models\GrupoDocente::where('idmateria', $materiaSeleccionada->id)
                                    ->where('codigogrupo', $grupoSeleccionado->codigo)
                                    ->first();
            }
        }

        $examenes = DB::table('examen')->get();
        if ($examenes->isEmpty()) {
            DB::table('examen')->insert([
                ['nro' => 1, 'descripcion' => 'Primer Parcial', 'fecha' => now()],
                ['nro' => 2, 'descripcion' => 'Segundo Parcial', 'fecha' => now()],
                ['nro' => 3, 'descripcion' => 'Examen Final', 'fecha' => now()],
            ]);
            $examenes = DB::table('examen')->get();
        }

        $configAbierto = Configuracion::where('clave', 'registro_notas_estado')->value('valor') ?? 'cerrado';
        $configFin = Configuracion::where('clave', 'registro_notas_fin')->value('valor');
        $diasRestantes = null;
        if ($configAbierto == 'abierto' && $configFin) {
            $diasRestantes = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($configFin), false);
        }

        return view('admin.examenes.index', compact('materias', 'grupos', 'materiaSeleccionada', 'grupoSeleccionado', 'examenes', 'grupoDocenteInfo', 'configAbierto', 'diasRestantes'));
    }

    public function updateExamenes(Request $request)
    {
        $request->validate([
            'materia_id' => 'required|exists:materia,id',
            'grupo_id' => 'required|exists:grupo,codigo',
            'examenes' => 'required|array',
            'examenes.*.descripcion' => 'required|string|max:255',
            'examenes.*.fecha' => 'nullable|date'
        ]);

        // Guardamos las descripciones globalmente (ya que descripcion no esta en grupodocente)
        // Pero las fechas las guardamos en grupodocente
        foreach ($request->examenes as $nro => $datos) {
            DB::table('examen')->where('nro', $nro)->update([
                'descripcion' => $datos['descripcion']
            ]);
        }

        DB::table('grupodocente')
            ->where('idmateria', $request->materia_id)
            ->where('codigogrupo', $request->grupo_id)
            ->update([
                'fecha_examen1' => $request->examenes[1]['fecha'] ?? null,
                'fecha_examen2' => $request->examenes[2]['fecha'] ?? null,
                'fecha_examen3' => $request->examenes[3]['fecha'] ?? null,
            ]);

        // Registrar en bitácora
        $usuarioDb = \App\Models\Usuario::where('email', \Illuminate\Support\Facades\Auth::user()->email)->first();
        if ($usuarioDb) {
            \App\Models\Bitacora::create([
                'accion' => 'Modificación de fechas de exámenes: Grupo ' . $request->grupo_id,
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'ip' => $request->ip(),
                'ciusuario' => $usuarioDb->ci
            ]);
        }

        return redirect()->route('admin.examenes.index', ['materia_id' => $request->materia_id, 'grupo_id' => $request->grupo_id])->with('success', 'Fechas de exámenes actualizadas exitosamente.');
    }

    public function updateConfigNotas(Request $request)
    {
        $request->validate([
            'accion' => 'required|in:abrir,cerrar',
            'dias' => 'nullable|integer|min:1'
        ]);

        if ($request->accion == 'abrir') {
            $dias = (int) ($request->dias ?? 7);
            $fechaFin = now()->addDays($dias);
            Configuracion::updateOrCreate(['clave' => 'registro_notas_estado'], ['valor' => 'abierto']);
            Configuracion::updateOrCreate(['clave' => 'registro_notas_fin'], ['valor' => $fechaFin->format('Y-m-d H:i:s')]);
            return back()->with('success', 'Registro de notas habilitado por ' . $dias . ' días.');
        } else {
            Configuracion::updateOrCreate(['clave' => 'registro_notas_estado'], ['valor' => 'cerrado']);
            return back()->with('success', 'Registro de notas cerrado manualmente.');
        }
    }
}
