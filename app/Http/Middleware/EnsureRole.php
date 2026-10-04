<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureRole {
    public function handle(Request $request, Closure $next, ...$roles) {
        $user=$request->user();
        $current=strtolower((string)($user?->roleRelation?->name ?? $user?->role));
        abort_unless($user && in_array($current,array_map('strtolower',$roles),true),403);
        return $next($request);
    }
}
