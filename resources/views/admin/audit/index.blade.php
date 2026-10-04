@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">Security observability</span><h1>Audit log</h1><p>Review security-sensitive and administrative activity.</p></div></div>
<section class="panel table-panel"><div class="panel-head"><h2>Recent events</h2><span>{{ $logs->total() }} events</span></div><div class="table-wrap"><table><thead><tr><th>Time</th><th>Actor</th><th>Action</th><th>Result</th><th>IP</th><th>Request ID</th></tr></thead><tbody>@forelse($logs as $log)<tr><td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td><td>{{ $log->user?->email ?? 'System/guest' }}</td><td><code>{{ $log->action }}</code></td><td><span class="badge {{ $log->result==='success'?'':'danger' }}">{{ $log->result }}</span></td><td>{{ $log->ip_address }}</td><td><code>{{ $log->request_id ?? '—' }}</code></td></tr>@empty<tr><td colspan="6">No audit events yet.</td></tr>@endforelse</tbody></table></div>{{ $logs->links() }}</section>
@endsection
