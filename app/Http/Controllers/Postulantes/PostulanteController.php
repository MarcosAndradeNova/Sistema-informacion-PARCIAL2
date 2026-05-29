<?php

namespace App\Http\Controllers\Postulantes;

use App\Http\Controllers\Controller;

use App\Models\Postulante;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StorePostulanteRequest;
use App\Http\Requests\UpdatePostulanteRequest;
use App\Models\Carrera;
use Illuminate\Support\Facades\Hash;

class PostulanteController extends Controller
{
    public function index(Request $request)
    {
        $query = Postulante::with(['usuario', 'primeraOpcion', 'segundaOpcion']);
        
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->whereHas('usuario', function($q) use ($search) {
                $q->where(DB::raw('LOWER(nombres)'), 'LIKE', "%{$search}%")
                  ->orWhere(DB::raw('LOWER(apellidos)'), 'LIKE', "%{$search}%")
                  ->orWhere(DB::raw('LOWER(ci)'), 'LIKE', "%{$search}%")
                  ->orWhere(DB::raw('LOWER(correo_electronico)'), 'LIKE', "%{$search}%");
            });
        }
        
        $postulantes = $query->paginate(10)->withQueryString();
        return view('postulantes.index', compact('postulantes'));
    }

    public function create()
    {
        $carreras = Carrera::all();
        return view('postulantes.create', compact('carreras'));
    }

    public function store(StorePostulanteRequest $request)
    {
        DB::transaction(function () use ($request) {
            Usuario::create([
                'ci' => $request->ci,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'correo_electronico' => $request->correo_electronico,
                'password' => Hash::make($request->ci), // Default password
                'rol' => 'POSTULANTE',
            ]);

            Postulante::create([
                'ci_usuario' => $request->ci,
                'carrera_primera_opcion' => $request->carrera_primera_opcion,
                'carrera_segunda_opcion' => $request->carrera_segunda_opcion,
                'colegio_procedencia' => $request->colegio_procedencia,
                'ciudad' => $request->ciudad,
                'titulo_bachiller' => $request->has('titulo_bachiller'),
                'otros_requisitos' => $request->otros_requisitos,
                'pago_efectuado' => false,
            ]);
        });

        return redirect()->route('postulantes.index')->with('success', 'Postulante registrado correctamente.');
    }

    public function edit($ci)
    {
        $postulante = Postulante::with('usuario')->findOrFail($ci);
        $carreras = Carrera::all();
        return view('postulantes.edit', compact('postulante', 'carreras'));
    }

    public function update(UpdatePostulanteRequest $request, $ci)
    {
        $postulante = Postulante::findOrFail($ci);
        $usuario = Usuario::findOrFail($ci);

        DB::transaction(function () use ($request, $usuario, $postulante) {
            // Update the CI if it changed
            if ($usuario->ci !== $request->ci) {
                // Since it's a primary key, it's complex to update.
                // We'll update other fields first.
                $usuario->ci = $request->ci;
            }

            $usuario->update([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'correo_electronico' => $request->correo_electronico,
            ]);

            $postulante->update([
                'carrera_primera_opcion' => $request->carrera_primera_opcion,
                'carrera_segunda_opcion' => $request->carrera_segunda_opcion,
                'colegio_procedencia' => $request->colegio_procedencia,
                'ciudad' => $request->ciudad,
                'titulo_bachiller' => $request->has('titulo_bachiller'),
                'otros_requisitos' => $request->otros_requisitos,
            ]);
        });

        return redirect()->route('postulantes.index')->with('success', 'Postulante actualizado correctamente.');
    }

    public function destroy($ci)
    {
        DB::transaction(function () use ($ci) {
            // Eliminar al usuario elimina en cascada al postulante
            Usuario::where('ci', $ci)->delete();
        });

        return redirect()->route('postulantes.index')->with('success', 'Postulante eliminado correctamente.');
    }
}
