<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Docente;

class AsignacionController extends Controller
{
    public function index()
    {
        // Solo mostrar docentes que estén aprobados para asignarles carga horaria
        $docentes = Usuario::where('tipo', 'D')
            ->join('docente', 'usuario.ci', '=', 'docente.ciusuario')
            ->where('docente.estado', 'APROBADO')
            ->select('usuario.*', 'docente.estado as estado_docente', 'docente.idmateria')
            ->get();

        return view('admin.asignacion.index', compact('docentes'));
    }
}
