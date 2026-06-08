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

        $postulante = Postulante::where('ci_usuario', $usuario->ci)->first();
        if (!$postulante || $postulante->estado_admision !== 'PAGO_PENDIENTE') {
            return redirect()->route('inscripcion.estado')->with('error', 'No estás habilitado para realizar el pago en este momento.');
        }

        return view('pago.create', compact('usuario', 'postulante'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:QR,TARJETA'
        ]);

        $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
        $postulante = Postulante::where('ci_usuario', $usuario->ci)->firstOrFail();
        
        // Verificar que realmente debe pagar
        if ($postulante->estado_admision !== 'PAGO_PENDIENTE') {
            return redirect()->route('inscripcion.estado')->with('error', 'El pago ya fue procesado o no es requerido.');
        }

        // Generar ID manual
        $nuevoId = Pago::max('id') + 1;

        // Crear el pago
        $pago = Pago::create([
            'id' => $nuevoId,
            'numero_recibo' => 'REC-' . strtoupper(uniqid()),
            'monto' => 300.00,
            'metodo_pago' => $request->metodo_pago,
            'estado' => 'Pagado',
            'fecha' => now()->toDateString(),
            'ci_usuario' => $usuario->ci
        ]);

        // Actualizar postulacion
        $postulacion = Postulacion::where('ci_usuario', $usuario->ci)
                        ->orderBy('cod_postulacion', 'desc')
                        ->first();
                        
        if ($postulacion) {
            $postulacion->id_pago = $pago->id;
            $postulacion->save();
        }

        // Cambiar estado a POSTULANTE_ACTIVO
        $postulante->estado_admision = 'POSTULANTE_ACTIVO';
        
        // Asignación automática a un grupo con cupo disponible
        $grupos = \App\Models\Grupo::where('estado', 1)->withCount('postulantes')->get();
        $grupoDisponible = $grupos->first(function ($g) {
            return $g->postulantes_count < $g->capacidad;
        });
            
        if (!$grupoDisponible) {
            $ultimoGrupo = \App\Models\Grupo::count();
            $letra = chr(65 + $ultimoGrupo);
            $grupoDisponible = \App\Models\Grupo::create([
                'nombre' => 'Grupo ' . $letra,
                'capacidad' => 70,
                'estado' => true
            ]);
        }
        
        $postulante->grupo_id = $grupoDisponible->id;

        $postulante->save();

        return redirect()->route('inscripcion.estado')->with('success', '¡Pago confirmado! Has completado tu inscripción y ahora eres un Postulante Activo.');
    }
}
