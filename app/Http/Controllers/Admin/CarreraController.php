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
        // Cargar los cupos y semestres desde la tabla 'ofrece'
        foreach ($carreras as $carrera) {
            $ofrece = \Illuminate\Support\Facades\DB::table('ofrece')
                ->where('codigocarre', $carrera->codigo)
                ->first();
                
            $carrera->estado = $carrera->estado ?? 'HABILITADO';
            $carrera->cupo = $ofrece ? $ofrece->cupo : 0;
            if ($ofrece) {
                $semestreObj = \Illuminate\Support\Facades\DB::table('semestre')->where('id', $ofrece->idsemestre)->first();
                $carrera->semestre = $semestreObj ? $semestreObj->semestre . '/' . $semestreObj->año : '1/2026';
            } else {
                $carrera->semestre = '1/2026';
            }
        }
        return view('admin.carreras.index', compact('carreras'));
    }

    public function update(Request $request)
    {
        $action = $request->input('action', 'update');

        if ($action === 'create') {
            $data = $request->validate([
                'codigo' => 'required|string|max:20|unique:carrera,codigo',
                'nombre' => 'required|string|max:150',
                'estado' => 'required|in:HABILITADO,INHABILITADO',
            ]);

            Carrera::create([
                'codigo' => strtoupper(trim($data['codigo'])),
                'nombre' => $data['nombre'],
                'estado' => $data['estado'],
            ]);

            // Crea registro base en ofrece para mostrar cupo/gestion en la tabla.
            if (!\Illuminate\Support\Facades\DB::table('ofrece')->where('codigocarre', strtoupper(trim($data['codigo'])))->exists()) {
                \Illuminate\Support\Facades\DB::table('ofrece')->insert([
                    'codigocarre' => strtoupper(trim($data['codigo'])),
                    'idsemestre' => 1,
                    'cupo' => 0,
                ]);
            }

            return redirect()->route('admin.carreras.index')->with('success', 'Carrera creada correctamente.');
        }

        $data = $request->validate([
            'carreras' => 'required|array',
            'carreras.*.estado' => 'required|in:HABILITADO,INHABILITADO',
            'carreras.*.cupo' => 'required|integer|min:0',
            'carreras.*.semestre' => 'required|string|max:20',
        ]);

        // Itera y actualiza cupos y semestres de cada carrera
        foreach ($data['carreras'] as $codigo => $carreraData) {
            Carrera::where('codigo', $codigo)->update([
                'estado' => $carreraData['estado'],
            ]);

            // Buscamos si existe un registro en 'ofrece'
            $existe = \Illuminate\Support\Facades\DB::table('ofrece')
                ->where('codigocarre', $codigo)
                ->exists();
                
            if ($existe) {
                \Illuminate\Support\Facades\DB::table('ofrece')
                    ->where('codigocarre', $codigo)
                    ->update([
                        'cupo' => $carreraData['cupo']
                    ]);
            } else {
                // Asumimos un idsemestre por defecto (1) si no existe
                \Illuminate\Support\Facades\DB::table('ofrece')->insert([
                    'codigocarre' => $codigo,
                    'idsemestre' => 1,
                    'cupo' => $carreraData['cupo']
                ]);
            }
        }

        return back()->with('success', 'Carreras actualizadas correctamente.');
    }
}
