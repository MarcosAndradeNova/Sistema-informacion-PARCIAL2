<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

        if (!$usuario || $usuario->tipo !== 'A') {
            abort(403, 'Acceso denegado. Se requieren permisos de Administrador.');
        }

        return $next($request);
    }
}
