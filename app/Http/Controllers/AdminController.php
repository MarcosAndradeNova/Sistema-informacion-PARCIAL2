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
            ->join('usuario', 'postulante.ci_usuario', '=', 'usuario.ci')
            ->select('postulante.*', 'usuario.nombre', 'usuario.apellido_pat', 'usuario.apellido_mat', 'usuario.email')
            ->orderBy('usuario.apellido_pat')
            ->get();

        return view('admin.postulantes', compact('postulantes'));
    }

    public function aprobarDocumentos(Request $request, $ci)
    {
        $postulante = Postulante::where('ci_usuario', $ci)->firstOrFail();
        $postulante->estado_admision = 'PAGO_PENDIENTE';
        $postulante->observaciones_documentos = null;
        $postulante->save();

        return redirect()->route('admin.postulantes')->with('success', 'Documentos aprobados exitosamente.');
    }

    public function rechazarDocumentos(Request $request, $ci)
    {
        $request->validate([
            'observaciones' => 'required|string|max:1000'
        ]);

        $postulante = Postulante::where('ci_usuario', $ci)->firstOrFail();
        $postulante->estado_admision = 'DOCUMENTOS_RECHAZADOS';
        $postulante->observaciones_documentos = $request->observaciones;
        $postulante->save();

        return redirect()->route('admin.postulantes')->with('error', 'Documentos observados correctamente.');
    }
}
