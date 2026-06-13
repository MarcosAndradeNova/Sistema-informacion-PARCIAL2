<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Postulante;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $postulantes = DB::table('postulante')
            ->join('usuario', 'postulante.ciusuario', '=', 'usuario.ci')
            ->select('postulante.*', 'usuario.nombre', 'usuario.apellidopat', 'usuario.apellidomat', 'usuario.email')
            ->orderBy('usuario.apellidopat')
            ->get();

        return view('admin.postulantes', compact('postulantes'));
    }

    public function aprobarDocumentos(Request $request, $ci)
    {
        $postulante = Postulante::where('ciusuario', $ci)->firstOrFail();
        $postulante->estadodocum = 'APROBADO';
        // $postulante->observaciones_documentos = null;
        $postulante->save();

        return redirect()->route('admin.postulantes')->with('success', 'Documentos aprobados exitosamente.');
    }

    public function rechazarDocumentos(Request $request, $ci)
    {
        $request->validate([
            'observaciones' => 'required|string|max:1000'
        ]);

        $postulante = Postulante::where('ciusuario', $ci)->firstOrFail();
        $postulante->estadodocum = 'RECHAZADO';
        // $postulante->observaciones_documentos = $request->observaciones;
        $postulante->save();

        return redirect()->route('admin.postulantes')->with('error', 'Documentos observados correctamente.');
    }

    public function enviarEnlacePago(Request $request, $ci)
    {
        $postulante = Postulante::where('ciusuario', $ci)->firstOrFail();
        
        if ($postulante->estadodocum !== 'APROBADO') {
            return redirect()->route('admin.postulantes')->with('error', 'El postulante debe estar aprobado para enviar el enlace de pago.');
        }

        $usuario = \App\Models\Usuario::where('ci', $ci)->firstOrFail();
        $enlace = \Illuminate\Support\Facades\URL::signedRoute('pago.pasarela', ['ci' => $ci], now()->addHours(48));

        try {
            \Illuminate\Support\Facades\Mail::to($usuario->email)->send(new \App\Mail\PagoEnlaceMail($enlace, $usuario->nombre));
        } catch (\Exception $e) {
            // Loguear el error o continuar en local
        }

        // Bitácora
        try {
            DB::table('bitacora')->insert([
                'usuario' => auth()->user()->email,
                'accion' => "Envió enlace de pago a postulante con CI: $ci",
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString()
            ]);
        } catch (\Exception $e) { }

        return redirect()->route('admin.postulantes')->with('success', 'Enlace de pago generado y enviado al correo del postulante.');
    }
}
