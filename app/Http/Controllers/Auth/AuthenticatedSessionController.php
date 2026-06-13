<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Obtenemos el usuario autenticado e intentamos buscar su registro completo
        $user = Auth::user();
        $usuario = \App\Models\Usuario::where('email', $user->email)->first();
        
        if (!$usuario) {
            return redirect()->route('dashboard');
        }

        // Verificamos si es docente
        if ($usuario->tipo === 'D') {
            if ($usuario->true /* estado_aprobacion removed */) {
                return redirect()->intended(route('dashboard', absolute: false));
            }
            return redirect()->route('docente.pendiente');
        }

        // Verificamos si es administrador o coordinador
        if ($usuario->tipo === 'A' || $usuario->tipo === 'C') {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Buscamos si el usuario tiene un registro como postulante en el sistema
        $postulante = \App\Models\Postulante::where('ciusuario', $usuario->ci)->first();

        if (!$postulante) {
            // Si no tiene ficha de postulante ni es otro rol, mandarlo al dashboard vacío o con error
            return redirect()->route('dashboard')->with('error', 'Tu cuenta no tiene una ficha de postulante asignada. Comunícate con administración.');
        } elseif ($postulante->estadodocum == 'INSCRITO') {
            // Redirigimos al dashboard principal si el postulante ya está activo
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return redirect()->route('inscripcion.estado');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Sesión cerrada correctamente.');
    }
}
