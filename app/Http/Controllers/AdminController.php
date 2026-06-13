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

        return view('admin.postulantes.index', compact('postulantes'));
    }

    public function create()
    {
        $carreras = \App\Models\Carrera::all();
        return view('admin.postulantes.create', compact('carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'ci' => 'required|string|max:20|unique:usuario,ci',
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
        ]);

        DB::transaction(function () use ($request) {
            // 1. Crear el auth user (Users)
            $user = \App\Models\User::create([
                'name' => $request->nombre,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->ci),
                'role' => 'user' // cambiará a postulante al pagar
            ]);

            // 2. Crear el Usuario (Datos personales)
            $usuario = \App\Models\Usuario::create([
                'ci' => $request->ci,
                'nombre' => $request->nombre,
                'apellidopat' => $request->apellidopat,
                'apellidomat' => $request->apellidomat,
                'fechanac' => $request->fechanac,
                'sexo' => $request->sexo,
                'nacionalidad' => $request->nacionalidad,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'tipo' => 'P'
            ]);

            // 3. Crear el Postulante
            \App\Models\Postulante::create([
                'ciusuario' => $usuario->ci,
                'rude' => $request->rude,
                'colegioprocedencia' => $request->colegioprocedencia,
                'ciudad' => $request->ciudad,
                'titulobachiller' => !empty($request->titulobachiller),
                'estadodocum' => 'PENDIENTE',
            ]);

            // 4. Crear la Postulacion
            $nuevoCod = \App\Models\Postulacion::max('codpost') ?? 0;
            $nuevoCod++;
            
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

            $rol = DB::table('rol')->where('descripcion', 'ilike', '%Postulante%')->first();
            if (!$rol) {
                $maxCod = DB::table('rol')->max('cod') ?? 0;
                $codrol = $maxCod + 1;
                DB::table('rol')->insert(['cod' => $codrol, 'descripcion' => 'Postulante']);
            } else {
                $codrol = $rol->cod;
            }

            $postulacion = \App\Models\Postulacion::create([
                'codpost' => $nuevoCod,
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString(),
                'idadmision' => $idadmision,
                'idsemestre' => $idsemestre,
                'codrol' => $codrol,
                'ciusuario' => $usuario->ci
            ]);

            // 5. Crear la Inscribe para las carreras
            \App\Models\Inscribe::create([
                'codpost' => $postulacion->codpost,
                'codigocarrera' => $request->carrera_primera_opcion,
                'opcion' => 1
            ]);

            \App\Models\Inscribe::create([
                'codpost' => $postulacion->codpost,
                'codigocarrera' => $request->carrera_segunda_opcion,
                'opcion' => 2
            ]);
        });

        return redirect()->route('admin.postulantes')->with('success', 'El postulante ha sido registrado correctamente. Verifique sus documentos para enviarle el enlace de pago.');
    }

    public function aprobarDocumentos(Request $request, $ci)
    {
        $postulante = Postulante::where('ciusuario', $ci)->firstOrFail();
        $postulante->estadodocum = 'APROBADO';
        $postulante->save();

        // Send payment link automatically
        $usuario = \App\Models\Usuario::where('ci', $ci)->firstOrFail();
        $enlace = \Illuminate\Support\Facades\URL::signedRoute('pago.pasarela', ['ci' => $ci], now()->addHours(48));

        try {
            \Illuminate\Support\Facades\Mail::to($usuario->email)->send(new \App\Mail\PagoEnlaceMail($enlace, $usuario->nombre));
        } catch (\Exception $e) {
            // Ignorar para pruebas
        }

        try {
            DB::table('bitacora')->insert([
                'usuario' => auth()->user()->email,
                'accion' => "Aprobó documentos y envió enlace de pago a postulante con CI: $ci",
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString()
            ]);
        } catch (\Exception $e) { }

        return redirect()->route('admin.postulantes')->with('success', 'Documentos aprobados exitosamente y enlace de pago enviado al estudiante.');
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
