<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Postulacion;
use App\Models\Inscribe;
use App\Models\Carrera;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    /**
     * Muestra el formulario de inscripción para un usuario registrado.
     */
    public function create()
    {
        $user = Auth::user();
        
        $usuario = Usuario::where('email', $user->email)->first();
        
        if ($usuario && Postulante::where('ci_usuario', $usuario->ci)->exists()) {
            return redirect()->route('inscripcion.estado');
        }

        $carreras = Carrera::all();
        return view('inscripcion.formulario', compact('carreras'));
    }

    /**
     * Guarda los datos personales y académicos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|max:20',
            'nombre' => 'required|string|max:50',
            'apellido_pat' => 'required|string|max:100',
            'apellido_mat' => 'nullable|string|max:100',
            'fechanac' => 'required|date',
            'sexo' => 'required|string|size:1',
            'nacionalidad' => 'required|string|max:50',
            'direccion' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            
            'colegio_proc' => 'required|string|max:100',
            'ciudad' => 'required|string|max:50',
            'rude' => 'nullable|string|max:50',
            'tit_bachiller_nro' => 'required|string|max:50',
            
            'carrera_primera_opcion' => 'required|exists:carrera,codigo',
            'carrera_segunda_opcion' => 'required|different:carrera_primera_opcion|exists:carrera,codigo',
        ], [
            'carrera_segunda_opcion.different' => 'La segunda opción debe ser una carrera distinta a la primera.',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            // 1. Crear el Usuario (Datos personales)
            $usuario = Usuario::updateOrCreate(
                ['email' => $user->email],
                [
                    'ci' => $request->ci,
                    'nombre' => $request->nombre,
                    'apellido_pat' => $request->apellido_pat,
                    'apellido_mat' => $request->apellido_mat,
                    'fechanac' => $request->fechanac,
                    'sexo' => $request->sexo,
                    'nacionalidad' => $request->nacionalidad,
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono,
                    'tipo' => 'P'
                ]
            );

            // 2. Crear el Postulante
            Postulante::updateOrCreate(
                ['ci_usuario' => $usuario->ci],
                [
                    'rude' => $request->rude,
                    'colegio_proc' => $request->colegio_proc,
                    'ciudad' => $request->ciudad,
                    'tit_bachiller_nro' => $request->tit_bachiller_nro,
                    'estado_admision' => 'DOCUMENTOS_PENDIENTES',
                ]
            );

            // 3. Crear la Postulacion (Calculando el ID manual ya que la BD no tiene AUTO_INCREMENT)
            $nuevoCod = Postulacion::max('cod_postulacion') + 1;
            
            $postulacion = Postulacion::create([
                'cod_postulacion' => $nuevoCod,
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString(),
                'ci_usuario' => $usuario->ci,
                'estado_doc' => 'PENDIENTE',
            ]);

            // 4. Crear la Inscribe para las carreras (opcion 1 y 2)
            Inscribe::create([
                'codigo_post' => $postulacion->cod_postulacion,
                'codigo_carrera' => $request->carrera_primera_opcion,
                'opcion' => 1
            ]);

            Inscribe::create([
                'codigo_post' => $postulacion->cod_postulacion,
                'codigo_carrera' => $request->carrera_segunda_opcion,
                'opcion' => 2
            ]);
        });

        return redirect()->route('inscripcion.estado')->with('success', 'Tus datos han sido registrados correctamente. Por favor verifica el siguiente paso.');
    }

    /**
     * Muestra el estado actual del proceso de admisión.
     */
    public function estado()
    {
        $user = Auth::user();
        $usuario = Usuario::where('email', $user->email)->first();

        if (!$usuario) {
            return redirect()->route('inscripcion.create');
        }

        $postulante = Postulante::where('ci_usuario', $usuario->ci)->first();

        if (!$postulante) {
            return redirect()->route('inscripcion.create');
        }

        return view('inscripcion.estado', compact('postulante'));
    }
}
