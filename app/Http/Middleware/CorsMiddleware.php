<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $allowed = config('security.cors_allowed_origins', []);
        $origin = $request->headers->get('Origin');

        if (!$origin || !in_array($origin, $allowed, true)) {
            return $next($request);
        }

        $headers = [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-API-Key, X-CSRF-TOKEN, Idempotency-Key, X-Request-ID',
            'Access-Control-Max-Age' => '600',
            'Vary' => 'Origin',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response('', 204)->withHeaders($headers);
        }

        return $next($request)->withHeaders([
            'Access-Control-Allow-Origin' => $origin,
            'Vary' => 'Origin',
        ]);
    }
}
