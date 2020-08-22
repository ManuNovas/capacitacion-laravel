<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificaAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Verificar que en la variable de sesión exista el administrador
         *  Si existe seguimos con el flujo ideal del sistema
         *  Si no existe, hacemos redirect a la pantalla de login
         */
        if (session()->has('administrador')) {
            return $next($request);
        } else {
            return redirect()->route('login.admon');
        }
    }
}
