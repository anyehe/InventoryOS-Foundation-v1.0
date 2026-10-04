<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApiAbility
{
    public function handle(Request $request, Closure $next, string $ability)
    {
        $key = $request->attributes->get('apiKey');
        $abilities = $key?->abilities ?? [];
        if (!in_array('*', $abilities, true) && !in_array($ability, $abilities, true)) {
            return response()->json(['message' => 'API key is not authorized for this operation.'], 403);
        }
        return $next($request);
    }
}
