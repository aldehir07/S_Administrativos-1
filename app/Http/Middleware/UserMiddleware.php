<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user() || $request->user()->role !== 'user'){
            // Si no está autenticado o no es user, redirige al dashboard con mensaje
            return redirect(route('datos.index'))->with('mensaje2', 'No posee los permisos para acceder a esta sección');
        }
        return $next($request);
    }
}
