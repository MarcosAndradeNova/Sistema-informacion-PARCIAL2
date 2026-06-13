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
        
        if ($usuario && Postulante::where('ciusuario', $usuario->ci)->exists()) {
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

        // Auto-verificar pago si existe
        if ($postulante->estadodocum === 'APROBADO' || $postulante->estadodocum === 'VERIFICADO') {
            $pago = \App\Models\Pago::where('ciusuario', $usuario->ci)->first();
            if ($pago) {
                // Proceder a inscribir automáticamente
                DB::transaction(function () use ($postulante, $usuario, $pago) {
                    $ci = $usuario->ci;
                    $postulacion = Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
                    if ($postulacion) {
                        $postulacion->idpago = $pago->id;
                        
                        if (is_null($postulacion->codgrupo)) {
                            $grupos = \App\Models\Grupo::all();
                            $grupoDisponible = null;
                            foreach ($grupos as $g) {
                                $cupo = $g->cupo ?? 70;
                                $inscritos = \App\Models\Postulacion::where('codgrupo', $g->codigo)->count();
                                if ($inscritos < $cupo) {
                                    $grupoDisponible = $g;
                                    break;
                                }
                            }
                            if (!$grupoDisponible) {
                                $countGrupos = \App\Models\Grupo::count();
                                $nuevoCodigo = 'G' . ($countGrupos + 1);
                                $grupoDisponible = \App\Models\Grupo::create([
                                    'codigo' => $nuevoCodigo,
                                    'nombre' => 'Grupo ' . $nuevoCodigo,
                                    'cupo' => 70,
                                    'idturno' => 1
                                ]);
                            }
                            $postulacion->codgrupo = $grupoDisponible->codigo;
                        }
                        $postulacion->save();
                    }

                    $postulante->estadodocum = 'INSCRITO';
                    $postulante->save();

                    $authUser = \App\Models\User::where('email', $usuario->email)->first();
                    if ($authUser) {
                        $authUser->password = \Illuminate\Support\Facades\Hash::make($ci);
                        $authUser->role = 'postulante';
                        $authUser->save();
                    }

                    try {
                        DB::table('bitacora')->insert([
                            'usuario' => $usuario->nombre . ' ' . $usuario->apellidopat,
                            'accion' => "Inscripción completada automáticamente al detectar pago para CI: $ci.",
                            'fecha' => now()->toDateString(),
                            'hora' => now()->toTimeString()
                        ]);
                    } catch (\Exception $e) {}

                    try {
                        \Illuminate\Support\Facades\Mail::to($usuario->email)->send(new \App\Mail\CredencialesMail($usuario->email, $ci));
                    } catch (\Exception $e) {}
                });

                // Refrescar y redirigir
                Auth::loginUsingId($user->id);
                return redirect()->route('dashboard')->with('success', '¡Tu pago ha sido detectado automáticamente y ya eres Postulante Oficial!');
            }
        }

        return view('inscripcion.estado', compact('postulante'));
    }

    /**
     * Verifica asíncronamente si el pago fue registrado y completa la inscripción.
     */
    public function verificarPago(Request $request)
    {
        $user = Auth::user();
        $usuario = Usuario::where('email', $user->email)->firstOrFail();
        $ci = $usuario->ci;
        
        $postulante = Postulante::where('ciusuario', $ci)->firstOrFail();

        // Si ya está inscrito, no hacer nada
        if ($postulante->estadodocum === 'INSCRITO') {
            return redirect()->route('dashboard');
        }

        // Consultar la tabla de pagos
        $pago = \App\Models\Pago::where('ciusuario', $ci)->first();

        if (!$pago) {
            return redirect()->back()->withErrors(['pago' => 'Aún no se ha detectado el pago en nuestro sistema. Si ya pagaste, espera unos minutos e intenta de nuevo.']);
        }

        // Si pagó, procesamos la inscripción completa
        DB::transaction(function () use ($postulante, $usuario, $ci, $pago) {
            $postulacion = Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
            if ($postulacion) {
                $postulacion->idpago = $pago->id;
                
                // Asignar grupo si no tiene
                if (is_null($postulacion->codgrupo)) {
                    $grupos = \App\Models\Grupo::all();
                    $grupoDisponible = null;
                    
                    foreach ($grupos as $g) {
                        $cupo = $g->cupo ?? 70;
                        $inscritos = \App\Models\Postulacion::where('codgrupo', $g->codigo)->count();
                        if ($inscritos < $cupo) {
                            $grupoDisponible = $g;
                            break;
                        }
                    }

                    if (!$grupoDisponible) {
                        $countGrupos = \App\Models\Grupo::count();
                        $nuevoCodigo = 'G' . ($countGrupos + 1);
                        $grupoDisponible = \App\Models\Grupo::create([
                            'codigo' => $nuevoCodigo,
                            'nombre' => 'Grupo ' . $nuevoCodigo,
                            'cupo' => 70,
                            'idturno' => 1
                        ]);
                    }

                    $postulacion->codgrupo = $grupoDisponible->codigo;
                }
                
                $postulacion->save();
            }

            // 3. Actualizar estado del postulante a INSCRITO
            $postulante->estadodocum = 'INSCRITO';
            $postulante->save();

            // 4. Actualizar/Establecer contraseña del usuario
            $authUser = \App\Models\User::where('email', $usuario->email)->first();
            if ($authUser) {
                $authUser->password = \Illuminate\Support\Facades\Hash::make($ci);
                $authUser->role = 'postulante';
                $authUser->save();
            }

            // 5. Registrar en bitácora
            try {
                DB::table('bitacora')->insert([
                    'usuario' => $usuario->nombre . ' ' . $usuario->apellidopat,
                    'accion' => "Inscripción completada mediante verificación de pago asíncrona para CI: $ci.",
                    'fecha' => now()->toDateString(),
                    'hora' => now()->toTimeString()
                ]);
            } catch (\Exception $e) {}

            // 6. Enviar correo con credenciales
            try {
                \Illuminate\Support\Facades\Mail::to($usuario->email)->send(new \App\Mail\CredencialesMail($usuario->email, $ci));
            } catch (\Exception $e) {}
        });

        // Refrescar sesión para que tome el nuevo role "postulante" inmediatamente en el dashboard
        Auth::loginUsingId($user->id);

        return redirect()->route('dashboard')->with('success', '¡Felicidades! Tu pago ha sido verificado y tu inscripción fue completada con éxito.');
    }
}
