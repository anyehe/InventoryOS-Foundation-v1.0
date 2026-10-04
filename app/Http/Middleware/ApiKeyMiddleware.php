<?php
namespace App\Http\Middleware;
use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
class ApiKeyMiddleware {
    public function handle(Request $request, Closure $next) {
        $plain=$request->header(config('security.api_key_header'));
        if (!$plain) return response()->json(['message'=>'API key required.'],401);
        $key=ApiKey::where('key_hash',hash('sha256',$plain))->first();
        if (!$key || !$key->active()) return response()->json(['message'=>'Invalid or inactive API key.'],401);
        $limit=(int)config('security.api_rate_limit');
        $rateKey='api-key:'.$key->id;
        if (RateLimiter::tooManyAttempts($rateKey,$limit)) return response()->json(['message'=>'Rate limit exceeded.'],429);
        RateLimiter::hit($rateKey, (int) config('security.api_rate_window', 60));
        $key->forceFill(['last_used_at'=>now()])->saveQuietly();
        $request->attributes->set('apiKey',$key);
        return $next($request);
    }
}
