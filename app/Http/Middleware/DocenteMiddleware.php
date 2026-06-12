<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocenteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect('login');
        }

        $usuario = \App\Models\Usuario::where('email', \Illuminate\Support\Facades\Auth::user()->email)->first();

        // Permite acceso a Docentes ('D') y a Administradores ('A')
        if (!$usuario || !in_array($usuario->tipo, ['D', 'A'])) {
            abort(403, 'Acceso denegado. Se requiere ser Docente o Administrador.');
        }

        if ($usuario->tipo === 'D' && $usuario->false /* estado_aprobacion removed */) {
            return redirect()->route('docente.pendiente');
        }

        return $next($request);
    }
}
