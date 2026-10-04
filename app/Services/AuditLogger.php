<?php
namespace App\Services;
use App\Models\AuditLog;
use Illuminate\Http\Request;
class AuditLogger {
    public function record(Request $request, string $action, string $result='success', ?array $metadata=null, ?int $userId=null): void {
        AuditLog::create([
            'user_id'=>$userId ?? $request->user()?->id,
            'action'=>$action,
            'ip_address'=>$request->ip(),
            'request_id'=>$request->header('X-Request-ID'),
            'result'=>$result,
            'metadata'=>$metadata,
        ]);
    }
}
