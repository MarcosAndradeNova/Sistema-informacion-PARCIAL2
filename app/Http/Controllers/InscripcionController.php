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
use Illuminate\Validation\Rule;

class InscripcionController extends Controller
{
    /**
     * Muestra el formulario de inscripción para un usuario registrado.
     */
    public function create()
    {
        $user = Auth::user();
        
        $usuario = Usuario::where('email', $user->email)->first();
        
        if ($usuario && Postulante::where('ciusuario', $usuario->ci)->exists()) {
            return redirect()->route('inscripcion.estado');
        }

        $carreras = Carrera::where('estado', 'HABILITADO')->orderBy('nombre')->get();
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
            'apellidopat' => 'required|string|max:100',
            'apellidomat' => 'nullable|string|max:100',
            'fechanac' => 'required|date',
            'sexo' => 'required|string|size:1',
            'nacionalidad' => 'required|string|max:50',
            'direccion' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            
            'colegioprocedencia' => 'required|string|max:100',
            'ciudad' => 'required|string|max:50',
            'rude' => 'nullable|string|max:50',
            'titulobachiller' => 'required|string|max:50',
            
            'carrera_primera_opcion' => [
                'required',
                Rule::exists('carrera', 'codigo')->where('estado', 'HABILITADO'),
            ],
            'carrera_segunda_opcion' => [
                'required',
                'different:carrera_primera_opcion',
                Rule::exists('carrera', 'codigo')->where('estado', 'HABILITADO'),
            ],
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
                    'apellidopat' => $request->apellidopat,
                    'apellidomat' => $request->apellidomat,
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
                ['ciusuario' => $usuario->ci],
                [
                    'rude' => $request->rude,
                    'colegioprocedencia' => $request->colegioprocedencia,
                    'ciudad' => $request->ciudad,
                    'titulobachiller' => !empty($request->titulobachiller),
                    'estadodocum' => 'PENDIENTE',
                ]
            );

            // 3. Crear la Postulacion (Calculando el ID manual ya que la BD no tiene AUTO_INCREMENT)
            $nuevoCod = Postulacion::max('codpost') ?? 0;
            $nuevoCod++;
            
            // Garantizar que exista una admisión y semestre para evitar errores de llave foránea nula
            $admision = DB::table('admision')->first();
            if (!$admision) {
                DB::table('admision')->insert(['id' => 1, 'estado' => 'Activa']);
                $idadmision = 1;
            } else {
                $idadmision = $admision->id;
            }

            $semestre = DB::table('semestre')->first();
            if (!$semestre) {
                DB::table('semestre')->insert(['id' => 1, 'semestre' => 1, 'año' => date('Y')]);
                $idsemestre = 1;
            } else {
                $idsemestre = $semestre->id;
            }

            // Garantizar que exista el rol Postulante para evitar errores
            $rol = DB::table('rol')->where('descripcion', 'ilike', '%Postulante%')->first();
            if (!$rol) {
                $maxCod = DB::table('rol')->max('cod') ?? 0;
                $codrol = $maxCod + 1;
                DB::table('rol')->insert(['cod' => $codrol, 'descripcion' => 'Postulante']);
            } else {
                $codrol = $rol->cod;
            }

            $postulacion = Postulacion::create([
                'codpost' => $nuevoCod,
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString(),
                'idadmision' => $idadmision,
                'idsemestre' => $idsemestre,
                'codrol' => $codrol,
                'ciusuario' => $usuario->ci
            ]);

            // 4. Crear la Inscribe para las carreras (opcion 1 y 2)
            Inscribe::create([
                'codpost' => $postulacion->codpost,
                'codigocarrera' => $request->carrera_primera_opcion,
                'opcion' => 1
            ]);

            Inscribe::create([
                'codpost' => $postulacion->codpost,
                'codigocarrera' => $request->carrera_segunda_opcion,
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

        $postulante = Postulante::where('ciusuario', $usuario->ci)->first();

        if (!$postulante) {
            return redirect()->route('inscripcion.create');
        }

        return view('inscripcion.estado', compact('postulante'));
    }
}
