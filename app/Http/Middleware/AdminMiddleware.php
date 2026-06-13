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

        if (!$usuario || !in_array($usuario->tipo, ['A', 'C'])) {
            abort(403, 'Acceso denegado. Se requieren permisos de Administrador o Coordinador.');
        }

        if ($usuario->tipo === 'C') {
            $allowedRoutes = [
                'admin.grupos.index', 'admin.grupos.show',
                'admin.evaluaciones.index',
                'admin.postulantes',
                'admin.reportes.index',
                'dashboard'
            ];
            
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                abort(403, 'Acceso denegado. El Coordinador solo tiene permisos de lectura en módulos específicos.');
            }
        }

        return $next($request);
    }
}
