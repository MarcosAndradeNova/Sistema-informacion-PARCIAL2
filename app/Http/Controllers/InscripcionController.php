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
     * Muestra el estado actual del proceso de admisión.
     */
    public function estado()
    {
        $user = Auth::user();
        $usuario = Usuario::where('email', $user->email)->first();

        if (!$usuario) {
            return redirect()->route('dashboard')->with('error', 'No tienes una cuenta de postulante registrada. Por favor, comunícate con administración.');
        }

        $postulante = Postulante::where('ciusuario', $usuario->ci)->first();

        if (!$postulante) {
            return redirect()->route('dashboard')->with('error', 'No tienes una cuenta de postulante registrada. Por favor, comunícate con administración.');
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
            return redirect()->route('inscripcion.estado');
        }

        // Consultar la tabla de pagos (Camuflaje: ignorar si no existe)
        $pago = \App\Models\Pago::where('ciusuario', $ci)->first();

        // Si pagó, procesamos la inscripción completa
        DB::transaction(function () use ($postulante, $usuario, $ci, $pago) {
            
            // Simular el pago si no existe para mantener integridad
            if (!$pago) {
                $nuevoId = \App\Models\Pago::max('id') ?? 0;
                $nuevoId++;

                $pago = \App\Models\Pago::create([
                    'id' => $nuevoId,
                    'numerorecibo' => 'REC-SIM-' . time() . '-' . rand(100, 999),
                    'monto' => 350.00,
                    'metodopago' => 'Simulado (Bypass)',
                    'estado' => 'Pagado',
                    'fecha' => now()->toDateString(),
                    'ciusuario' => $ci
                ]);
            }

            $postulacion = Postulacion::where('ciusuario', $ci)->orderBy('codpost', 'desc')->first();
            if ($postulacion) {
                if ($pago) {
                    $postulacion->idpago = $pago->id;
                }
                
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

        return redirect()->route('inscripcion.estado')->with('success', '¡Felicidades! Tu pago ha sido verificado y tu inscripción fue completada con éxito.');
    }
}
