<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IdempotencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->method(), ['POST','PUT','PATCH','DELETE'], true)) return $next($request);
        $key = trim((string)$request->header(config('api.idempotency_header')));
        if ($key === '') return $next($request);
        if (strlen($key) > 100 || !preg_match('/^[A-Za-z0-9._:-]+$/', $key)) {
            return response()->json(['message'=>'Invalid Idempotency-Key.'], 422);
        }
        $apiKey = $request->attributes->get('apiKey');
        $fingerprint = hash('sha256', $request->method().'|'.$request->path().'|'.$request->getContent());
        $existing = DB::table('api_idempotency_keys')->where('api_key_id',$apiKey?->id)->where('idempotency_key',$key)->first();
        if ($existing) {
            if (!hash_equals($existing->request_hash, $fingerprint)) return response()->json(['message'=>'Idempotency key was already used with a different request.'],409);
            if ($existing->status_code !== null) return response($existing->response_body, $existing->status_code)->header('Content-Type','application/json')->header('X-Idempotent-Replay','true');
            return response()->json(['message'=>'Request with this Idempotency-Key is already being processed.'],409);
        }
        DB::table('api_idempotency_keys')->insert(['id'=>Str::uuid()->toString(),'api_key_id'=>$apiKey?->id,'idempotency_key'=>$key,'request_hash'=>$fingerprint,'created_at'=>now(),'updated_at'=>now()]);
        $response = $next($request);
        DB::table('api_idempotency_keys')->where('api_key_id',$apiKey?->id)->where('idempotency_key',$key)->update(['status_code'=>$response->getStatusCode(),'response_body'=>$response->getContent(),'updated_at'=>now()]);
        return $response->header('X-Idempotency-Key',$key);
    }
}
