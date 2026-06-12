<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Postulacion;
use App\Models\Pago;

class PagoController extends Controller
{
    public function create()
    {
        $usuario = Usuario::where('email', Auth::user()->email)->first();
        if (!$usuario || $usuario->tipo !== 'POSTULANTE') { // 'P' si actualizamos el tipo
            // Permitir 'P' o 'POSTULANTE'
            if($usuario->tipo !== 'P' && $usuario->tipo !== 'POSTULANTE') {
                return redirect()->route('dashboard');
            }
        }

        $postulante = Postulante::where('ciusuario', $usuario->ci)->first();
        if (!$postulante || $postulante->estadodocum !== 'APROBADO') {
            return redirect()->route('inscripcion.estado')->with('error', 'No estás habilitado para realizar el pago en este momento.');
        }

        return view('pago.create', compact('usuario', 'postulante'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'metodopago' => 'required|in:QR,TARJETA'
        ]);

        $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
        $postulante = Postulante::where('ciusuario', $usuario->ci)->firstOrFail();
        
        // Verificar que realmente debe pagar
        if ($postulante->estadodocum !== 'APROBADO') {
            return redirect()->route('inscripcion.estado')->with('error', 'El pago ya fue procesado o no es requerido.');
        }

        // Generar ID manual
        $nuevoId = Pago::max('id') ?? 0;
        $nuevoId++;

        // Crear el pago
        $pago = Pago::create([
            'id' => $nuevoId,
            'numerorecibo' => 'REC-' . strtoupper(uniqid()),
            'monto' => 300.00,
            'metodopago' => $request->metodopago,
            'estado' => 'Pagado',
            'fecha' => now()->toDateString(),
            'ciusuario' => $usuario->ci
        ]);

        // Actualizar postulacion
        $postulacion = Postulacion::where('ciusuario', $usuario->ci)
                        ->orderBy('codpost', 'desc')
                        ->first();
                        
        if ($postulacion) {
            $postulacion->idpago = $pago->id;
            $postulacion->save();
        }

        // Cambiar estado a INSCRITO
        $postulante->estadodocum = 'INSCRITO';
        $postulante->save();

        // Asignación automática de grupo en tiempo real
        if ($postulacion && is_null($postulacion->codgrupo)) {
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
            $postulacion->save();
        }

        return redirect()->route('inscripcion.estado')->with('success', '¡Pago confirmado! Has completado tu inscripción y ahora eres un Postulante Activo.');
    }
}
