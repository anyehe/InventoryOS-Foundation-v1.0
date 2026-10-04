@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">INVENTORY CORE</span><h1>Stock overview</h1><p>Monitor stock across warehouses and act on low inventory.</p></div></div>
@if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif
<div class="grid-2">
 <article class="panel"><div class="panel-head"><div><h2>Current stock</h2><span>Warehouse balances</span></div><a href="{{ route('inventory.movements') }}">View movements →</a></div><div class="toolbar"><form method="GET" class="toolbar" style="margin:0"><input name="search" value="{{ request('search') }}" placeholder="Search product or SKU"><select name="warehouse_id" class="select"><option value="">All warehouses</option>@foreach($warehouses as $w)<option value="{{ $w->id }}" @selected(request('warehouse_id')==$w->id)>{{ $w->name }}</option>@endforeach</select><button class="primary">Filter</button></form></div>
 <div class="table-wrap"><table><thead><tr><th>Product</th><th>Warehouse</th><th>On hand</th><th>Reorder</th><th>Status</th></tr></thead><tbody>@forelse($stocks as $s)<tr><td><b>{{ $s->product->name }}</b><small>{{ $s->product->sku }}</small></td><td>{{ $s->warehouse->name }}</td><td>{{ number_format($s->quantity,3) }}</td><td>{{ number_format($s->product->reorder_level,3) }}</td><td><span class="badge {{ $s->quantity <= $s->product->reorder_level ? 'danger' : 'success' }}">{{ $s->quantity <= $s->product->reorder_level ? 'Low stock' : 'In stock' }}</span></td></tr>@empty<tr><td colspan="5">No stock records yet.</td></tr>@endforelse</tbody></table></div>{{ $stocks->links() }}</article>
 <div>
  <article class="panel"><div class="panel-head"><div><h2>Adjust stock</h2><span>Positive adds; negative removes</span></div></div><form method="POST" action="{{ route('inventory.adjust') }}" class="form-grid">@csrf
  <label>Product<select name="product_id" required>@foreach(\App\Models\Product::orderBy('name')->get() as $p)<option value="{{ $p->id }}">{{ $p->name }} — {{ $p->sku }}</option>@endforeach</select></label>
  <label>Warehouse<select name="warehouse_id" required>@foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach</select></label>
  <label>Quantity<input type="number" step="0.001" name="quantity" required placeholder="e.g. 10 or -2"></label>
  <label>Note<input name="note" maxlength="500" placeholder="Reason for adjustment"></label><div class="form-actions"><button class="primary">Apply adjustment</button></div></form></article>
  <article class="panel"><div class="panel-head"><div><h2>Transfer stock</h2><span>Move inventory between locations</span></div></div><form method="POST" action="{{ route('inventory.transfer') }}" class="form-grid">@csrf
  <label>Product<select name="product_id" required>@foreach(\App\Models\Product::orderBy('name')->get() as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></label>
  <label>Quantity<input type="number" step="0.001" min="0.001" name="quantity" required></label>
  <label>From<select name="from_warehouse_id" required>@foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach</select></label>
  <label>To<select name="to_warehouse_id" required>@foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach</select></label>
  <label style="grid-column:1/-1">Note<input name="note" maxlength="500" placeholder="Optional transfer note"></label><div class="form-actions"><button class="primary">Transfer stock</button></div></form></article>
 </div>
</div>
@endsection
