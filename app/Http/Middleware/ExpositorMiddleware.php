<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpositorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        // Verifica si el usuario está autenticado en alguno de los guardias
        if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
            // Obtiene al usuario autenticado con el guardia 'expositor'
            $user = Auth::guard('expositor')->user();

            if ($user) {
                // Verifica si el usuario aún no ha cambiado su contraseña
                if (!$user->cambio_password) {
                    // Evitar redirigir si ya estamos en la ruta de cambio de contraseña
                    if ($request->route()->getName() !== 'expositor.change-password-view' && $request->route()->getName() !== 'expositor.change-password') {
                        return redirect()->route('expositor.change-password-view');
                    } else {
                        return $next($request);
                    }
                }

                $terminosCondiciones = \App\Models\Exposicion::where('id', $user->id_exposicion)->pluck('path_terminos_condiciones')->first();

                if ($terminosCondiciones) {
                    $expositor = \App\Models\Expositor::find($user->id);
                    if (!$expositor->acepto_terminos_condiciones && !$request->acepta_terminos_condiciones) {

                        // Verifica si ya estamos en la ruta de términos y condiciones
                        if ($request->route()->getName() !== 'expositor.terminos-condiciones') {
                            return redirect()->route('expositor.terminos-condiciones');
                        }
                    } else if (!$expositor->acepto_terminos_condiciones && $request->acepta_terminos_condiciones) {
                        $expositor = \App\Models\Expositor::find($user->id);
                        $expositor->acepto_terminos_condiciones = true;
                        $expositor->save();
                    }
                }
            }
            
            return $next($request);
        }

        // Si el usuario no está autenticado, redirige al login con error
        return redirect('/login')->withErrors('Acceso denegado.');
    }
}
