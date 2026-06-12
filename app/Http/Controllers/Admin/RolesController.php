<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function index()
    {
        // Traer a todos los usuarios del sistema (excepto el admin actual)
        $users = User::where('id', '!=', Auth::id())->paginate(15);
        
        // Sincronizar roles nulos para la vista (por datos heredados)
        foreach ($users as $user) {
            if (!$user->role) {
                $usuario = Usuario::where('email', $user->email)->first();
                if ($usuario) {
                    if ($usuario->tipo === 'A') $user->role = 'admin';
                    elseif ($usuario->tipo === 'D') $user->role = 'docente';
                    else $user->role = 'estudiante';
                } else {
                    $user->role = 'estudiante';
                }
                // Lo guardamos para que en futuras vistas ya no sea nulo
                $user->save();
            }
        }

        return view('admin.roles.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,docente,estudiante'
        ]);

        $user = User::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Actualizar la tabla de autenticación
            $user->role = $request->role;
            $user->save();

            // Sincronizar con la tabla usuario y la tabla específica de rol
            $usuario = Usuario::where('email', $user->email)->first();
            if ($usuario) {
                if ($request->role === 'admin') {
                    $usuario->tipo = 'A';
                    
                    // Insertar en tabla admin con codrol = 3 si no existe
                    $exists = DB::table('admin')->where('ciusuario', $usuario->ci)->exists();
                    if (!$exists) {
                        DB::table('admin')->insert([
                            'ciusuario' => $usuario->ci,
                            'codigo' => (int) $usuario->ci,
                            'codrol' => 3 // 3 = Admin en tabla rol
                        ]);
                    }
                    
                } elseif ($request->role === 'docente') {
                    $usuario->tipo = 'D';
                    
                    // Insertar en tabla docente con codrol = 2 si no existe
                    $exists = DB::table('docente')->where('ciusuario', $usuario->ci)->exists();
                    if (!$exists) {
                        DB::table('docente')->insert([
                            'ciusuario' => $usuario->ci,
                            'profesion' => 'Asignado por Administrador',
                            'codrol' => 2, // 2 = Docente en tabla rol
                            'estado' => 'APROBADO'
                        ]);
                    } else {
                        DB::table('docente')->where('ciusuario', $usuario->ci)->update(['estado' => 'APROBADO', 'codrol' => 2]);
                    }
                    
                } else {
                    $usuario->tipo = 'P';
                    
                    // Insertar en tabla postulante (no usa codrol directo pero le corresponde rol 1)
                    $exists = DB::table('postulante')->where('ciusuario', $usuario->ci)->exists();
                    if (!$exists) {
                        DB::table('postulante')->insert([
                            'ciusuario' => $usuario->ci,
                            'estadodocum' => 'REGISTRADO'
                        ]);
                    }
                }
                $usuario->save();
            }

            // Registrar en bitácora
            $adminUser = Usuario::where('email', Auth::user()->email)->first();
            if ($adminUser) {
                \App\Models\Bitacora::create([
                    'accion' => "Cambio de rol de {$user->email} a {$request->role}",
                    'fecha' => now()->format('Y-m-d'),
                    'hora' => now()->format('H:i:s'),
                    'ip' => $request->ip(),
                    'ciusuario' => $adminUser->ci
                ]);
            }

            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', "Rol de {$user->name} actualizado a " . ucfirst($request->role) . ".");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.roles.index')->with('error', 'Ocurrió un error al intentar cambiar el rol.');
        }
    }
}
