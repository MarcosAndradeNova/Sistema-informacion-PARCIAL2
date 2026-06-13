<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\User;

class CoordinadorController extends Controller
{
    public function index()
    {
        $coordinadores = Usuario::where('tipo', 'C')->get();
        return view('admin.coordinadores.index', compact('coordinadores'));
    }

    public function create()
    {
        return view('admin.coordinadores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|max:15|unique:usuario,ci',
            'nombre' => 'required|string|max:100',
            'apellidopat' => 'required|string|max:100',
            'apellidomat' => 'nullable|string|max:100',
            'email' => 'required|email|unique:usuario,email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Crear User para la autenticación
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellidopat,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'coordinador'
            ]);

            // Crear Usuario en el sistema
            Usuario::create([
                'ci' => $request->ci,
                'nombre' => $request->nombre,
                'apellidopat' => $request->apellidopat,
                'apellidomat' => $request->apellidomat ?? '',
                'email' => $request->email,
                'tipo' => 'C',
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.coordinadores.index')->with('success', 'Coordinador creado exitosamente.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.coordinadores.index')->with('error', 'Error al crear coordinador: ' . $e->getMessage());
        }
    }

    public function edit($ci)
    {
        $coordinador = Usuario::where('ci', $ci)->where('tipo', 'C')->firstOrFail();
        return view('admin.coordinadores.edit', compact('coordinador'));
    }

    public function update(Request $request, $ci)
    {
        $coordinador = Usuario::where('ci', $ci)->where('tipo', 'C')->firstOrFail();
        
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidopat' => 'required|string|max:100',
            'email' => 'required|email|unique:usuario,email,' . $ci . ',ci',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $user = User::where('email', $coordinador->email)->first();

            $coordinador->update([
                'nombre' => $request->nombre,
                'apellidopat' => $request->apellidopat,
                'apellidomat' => $request->apellidomat ?? '',
                'email' => $request->email,
            ]);

            if ($user) {
                $user->update([
                    'name' => $request->nombre . ' ' . $request->apellidopat,
                    'email' => $request->email,
                ]);
                if ($request->filled('password')) {
                    $user->update(['password' => bcrypt($request->password)]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.coordinadores.index')->with('success', 'Coordinador actualizado correctamente.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.coordinadores.index')->with('error', 'Error al actualizar coordinador: ' . $e->getMessage());
        }
    }

    public function destroy($ci)
    {
        $coordinador = Usuario::where('ci', $ci)->where('tipo', 'C')->firstOrFail();
        
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $user = User::where('email', $coordinador->email)->first();
            if ($user) {
                $user->delete();
            }
            $coordinador->delete();

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.coordinadores.index')->with('success', 'Coordinador eliminado permanentemente.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.coordinadores.index')->with('error', 'No se pudo eliminar al coordinador: ' . $e->getMessage());
        }
    }
}
