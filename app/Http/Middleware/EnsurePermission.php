<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AuditLogger;
class EnsurePermission {
    public function __construct(private AuditLogger $audit) {}
    public function handle(Request $request, Closure $next, string $permission): Response {
        abort_unless($request->user()?->hasPermission($permission), 403);
        return $next($request);
    }
}
