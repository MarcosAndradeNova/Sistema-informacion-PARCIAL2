<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacora;
use App\Models\Usuario;
use Carbon\Carbon;

class LogBitacora
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Capturar el usuario ANTES de la petición (útil para el logout)
        $usuarioAntes = Auth::check() ? Auth::user()->email : null;

        $response = $next($request);

        // Capturar el usuario DESPUÉS de la petición (útil para el login)
        $usuarioDespues = Auth::check() ? Auth::user()->email : null;

        $emailRelevante = $usuarioAntes ?: $usuarioDespues;

        // Solo registramos si hay un usuario identificable y el método modifica datos (POST, PUT, DELETE, PATCH)
        if ($emailRelevante && in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            
            // Buscar CI del usuario logueado
            $usuario = Usuario::where('email', $emailRelevante)->first();
            
            if ($usuario) {
                $ruta = $request->path();
                $metodo = $request->method();
                
                // Mapear rutas a acciones legibles
                $accion = $this->mapearAccion($metodo, $ruta);

                // Evitar guardar logs de validaciones fallidas (opcional, pero buena práctica)
                // excepto en login donde un redirect (302) es exitoso
                if ($response->getStatusCode() < 400 || str_contains($ruta, 'login')) {
                    Bitacora::insert([
                        'accion' => $accion,
                        'fecha' => Carbon::now('America/La_Paz')->format('Y-m-d'),
                        'hora' => Carbon::now('America/La_Paz')->format('H:i:s'),
                        'ip' => $request->ip(),
                        'ciusuario' => $usuario->ci
                    ]);
                }
            }
        }

        return $response;
    }

    private function mapearAccion($metodo, $ruta)
    {
        if (str_contains($ruta, 'login')) return 'Inicio de sesión';
        if (str_contains($ruta, 'logout')) return 'Cierre de sesión';
        
        $partes = explode('/', $ruta);
        $modulo = isset($partes[1]) ? $partes[1] : $partes[0];

        $operacion = 'Realizó una acción en';
        
        if ($metodo === 'POST') {
            if (str_contains($ruta, 'aprobar')) $operacion = 'Aprobó un registro en';
            elseif (str_contains($ruta, 'rechazar')) $operacion = 'Rechazó un registro en';
            elseif (str_contains($ruta, 'auto-asignar')) $operacion = 'Ejecutó auto-asignación en';
            else $operacion = 'Creó un nuevo registro en';
        } elseif ($metodo === 'PUT' || $metodo === 'PATCH') {
            $operacion = 'Actualizó un registro en';
        } elseif ($metodo === 'DELETE') {
            $operacion = 'Eliminó un registro en';
        }

        return "$operacion el módulo de " . strtoupper($modulo);
    }
}
