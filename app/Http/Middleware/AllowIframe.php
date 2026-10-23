<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowIframe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Agregar múltiples dominios permitidos para iframes
        $allowedDomains = [
            'http://gexpos.com',
            'https://www.etif.com.ar'
        ];

        $allowedDomainsString = implode(' ', $allowedDomains);

        // Usar Content-Security-Policy en lugar de X-Frame-Options
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'self' $allowedDomainsString");

        return $response;
    }
}
