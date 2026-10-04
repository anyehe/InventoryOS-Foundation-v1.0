<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestId
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->header('X-Request-ID');

        if (!$id || !preg_match('/^[A-Za-z0-9._-]{8,100}$/', $id)) {
            $id = (string) Str::uuid();
        }

        $request->attributes->set('requestId', $id);

        $response = $next($request);
        $response->headers->set('X-Request-ID', $id);

        return $response;
    }
}
