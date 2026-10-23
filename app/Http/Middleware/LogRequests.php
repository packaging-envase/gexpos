<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('REQUEST', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'input' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        $response = $next($request);

        Log::info('RESPONSE', [
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}