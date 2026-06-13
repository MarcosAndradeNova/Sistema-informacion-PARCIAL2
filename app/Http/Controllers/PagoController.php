<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulante;
use App\Models\Postulacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CredencialesMail;

class PagoController extends Controller
{
    /**
     * Muestra la pasarela de pagos simulada usando un enlace firmado.
     */
    public function showPasarela(Request $request, $ci)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'El enlace de pago es inválido o ha expirado.');
        }

        $postulante = Postulante::where('ciusuario', $ci)->firstOrFail();
        $usuario = Usuario::where('ci', $ci)->firstOrFail();

        // Verificar si ya está pagado
        if ($postulante->estadodocum === 'INSCRITO') {
            return redirect()->route('login')->with('info', 'El pago ya fue procesado anteriormente.');
        }

        return view('pago.pasarela', compact('postulante', 'usuario'));
    }

    /**
     * Procesa el pago y finaliza la inscripción.
     */
    public function procesarPago(Request $request, $ci)
    {
        $postulante = Postulante::where('ciusuario', $ci)->firstOrFail();
        $usuario = Usuario::where('ci', $ci)->firstOrFail();

        // Check if pago already exists
        $pagoExistente = \App\Models\Pago::where('ciusuario', $ci)->first();
        if (!$pagoExistente) {
            DB::transaction(function () use ($postulante, $usuario, $ci) {
                $nuevoId = \App\Models\Pago::max('id') ?? 0;
                $nuevoId++;

                $pago = \App\Models\Pago::create([
                    'id' => $nuevoId,
                    'numerorecibo' => 'REC-' . time() . '-' . rand(100, 999),
                    'monto' => 350.00,
                    'metodopago' => 'Tarjeta de Crédito/Débito',
                    'estado' => 'Pagado',
                    'fecha' => now()->toDateString(),
                    'ciusuario' => $ci
                ]);

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

                $user = User::where('email', $usuario->email)->first();
                if ($user) {
                    $user->password = Hash::make($ci);
                    $user->role = 'postulante';
                    $user->save();
                }

            });

            try {
                DB::table('bitacora')->insert([
                    'ciusuario' => $ci,
                    'ip' => $request->ip(),
                    'accion' => "Inscripción completada en pasarela para CI: $ci.",
                    'fecha' => now()->toDateString(),
                    'hora' => now()->toTimeString()
                ]);
            } catch (\Exception $e) {}

            try {
                Mail::to($usuario->email)->send(new CredencialesMail($usuario->email, $ci));
            } catch (\Exception $e) {}
        }

        return redirect()->route('login')->with('success', '¡Felicidades! Tu pago ha sido procesado con éxito y ya eres Postulante Oficial. Inicia sesión para ver tu nuevo panel.');
    }

    private function logBitacora($accion)
    {
        try {
            DB::table('bitacora')->insert([
                'ciusuario' => '0',
                'ip' => request()->ip() ?? '127.0.0.1',
                'accion' => $accion,
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString()
            ]);
        } catch (\Exception $e) {
            // Ignorar si falla la bitácora
        }
    }
}
