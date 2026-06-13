<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class DocenteRegistrationController extends Controller
{
    /**
     * Display the registration view for docentes.
     */
    public function create(): View
    {
        return view('auth.register-docente');
    }

    /**
     * Handle an incoming registration request for the base auth user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'docente',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('docente.inscripcion.create');
    }

    /**
     * Display the Ficha form for docentes.
     */
    public function createFicha()
    {
        $user = Auth::user();
        
        $usuario = Usuario::where('email', $user->email)->first();
        
        // Si ya tiene registro y es Docente, mostramos el estado de la ficha
        if ($usuario && $usuario->tipo === 'D') {
            return view('docente.pendiente');
        }

        $materias = \App\Models\Materia::orderBy('nombre')->get();

        return view('docente.formulario', compact('materias'));
    }

    /**
     * Store the Docente specific information.
     */
    public function storeFicha(Request $request): RedirectResponse
    {
        $request->validate([
            'ci' => 'required|string|max:20|unique:usuario,ci',
            'nombre' => 'required|string|max:50',
            'apellidopat' => 'required|string|max:100',
            'apellidomat' => 'nullable|string|max:100',
            'fechanac' => 'required|date',
            'sexo' => 'required|string|size:1',
            'nacionalidad' => 'required|string|max:50',
            'direccion' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'carrera' => 'required|string|max:100',
            'anios_experiencia' => 'required|integer|min:0',
            'nivel_formacion' => 'required|string|max:50',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materia,id',
        ]);

        $user = Auth::user();

        // Crear el Usuario (Datos personales del docente)
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
                'tipo' => 'D',
                // 'estado_aprobacion' => 'PENDIENTE'
            ]
        );

        // Crear el Docente (Datos académicos)
        \App\Models\Docente::updateOrCreate(
            ['ciusuario' => $usuario->ci],
            [
                'coddocente' => (int) $usuario->ci,
                'profesion' => $request->carrera,
                'experiencia' => $request->anios_experiencia,
                'nivelformacion' => $request->nivel_formacion,
                'codrol' => 2,
                'estado' => 'PENDIENTE',
                'idmateria' => !empty($request->materias) ? $request->materias[0] : null,
            ]
        );
        // Guardar las preferencias de materias
        \Illuminate\Support\Facades\DB::table('preferenciamat')->where('cidocente', $usuario->ci)->delete();
        foreach ($request->materias as $materiaId) {
            \Illuminate\Support\Facades\DB::table('preferenciamat')->insert([
                'cidocente' => $usuario->ci,
                'idmateria' => $materiaId
            ]);
        }

        return redirect()->route('docente.inscripcion.create')->with('success', 'Ficha de Docente completada.');
    }
}
