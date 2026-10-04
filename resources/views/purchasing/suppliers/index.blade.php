@extends('layouts.app')
@section('content')
<div class="page-head"><div><div class="eyebrow">Purchasing</div><h1>Suppliers</h1><p>Maintain supplier contacts and purchasing relationships.</p></div></div>
@if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif
<div class="grid-2">
<div class="panel"><div class="panel-head"><h2>Supplier directory</h2><span>{{ $suppliers->total() }} suppliers</span></div><div class="table-wrap"><table><thead><tr><th>Supplier</th><th>Code</th><th>Contact</th><th>Orders</th><th>Status</th></tr></thead><tbody>@forelse($suppliers as $s)<tr><td><strong>{{ $s->name }}</strong><small>{{ $s->email ?: 'No email' }}</small></td><td>{{ $s->code }}</td><td>{{ $s->phone ?: '—' }}</td><td>{{ $s->purchases_count }}</td><td><span class="status success">Active</span></td></tr>@empty<tr><td colspan="5" class="empty">No suppliers yet.</td></tr>@endforelse</tbody></table></div><div class="pagination">{{ $suppliers->links() }}</div></div>
<div class="panel"><div class="panel-head"><h2>Add supplier</h2><span>Required: name + code</span></div><form method="POST" action="{{ route('purchases.suppliers.store') }}" class="form-grid">@csrf<label>Name<input name="name" value="{{ old('name') }}" required></label><label>Code<input name="code" value="{{ old('code') }}" placeholder="SUP-001" required></label><label>Email<input type="email" name="email" value="{{ old('email') }}"></label><label>Phone<input name="phone" value="{{ old('phone') }}"></label><label>Tax ID<input name="tax_id" value="{{ old('tax_id') }}"></label><label>Address<input name="address" value="{{ old('address') }}"></label><div class="form-actions"><button class="btn primary">Create supplier</button></div></form></div>
</div>
@endsection
