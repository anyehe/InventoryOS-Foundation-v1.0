@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">Security</span><h1>User access</h1><p>Manage accounts and least-privilege roles.</p></div></div>
@if(session('status'))<div class="notice success">{{ session('status') }}</div>@endif
<div class="grid-2">
<section class="panel"><div class="panel-head"><h2>Create user</h2></div><form method="POST" action="{{ route('admin.users.store') }}" class="form-grid">@csrf
<label>Name<input name="name" required value="{{ old('name') }}"></label><label>Email<input type="email" name="email" required value="{{ old('email') }}"></label><label>Password<input type="password" name="password" minlength="12" required></label><label>Role<select name="role_id" required>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->label }}</option>@endforeach</select></label><button class="btn primary">Create user</button></form></section>
<section class="panel"><div class="panel-head"><h2>Access model</h2></div><div class="access-list"><div><b>Administrator</b><span>All permissions</span></div><div><b>Manager</b><span>Operations, sales and reports</span></div><div><b>Staff</b><span>Dashboard, inventory and sales</span></div></div></section>
</div>
<section class="panel table-panel"><div class="panel-head"><h2>Accounts</h2><span>{{ $users->total() }} total</span></div><div class="table-wrap"><table><thead><tr><th>User</th><th>Role</th><th>Created</th><th>Change role</th></tr></thead><tbody>@foreach($users as $user)<tr><td><strong>{{ $user->name }}</strong><small>{{ $user->email }}</small></td><td><span class="badge">{{ $user->roleRelation?->label ?? ucfirst($user->role) }}</span></td><td>{{ $user->created_at?->format('M d, Y') }}</td><td><form method="POST" action="{{ route('admin.users.role',$user) }}" class="inline-form">@csrf @method('PATCH')<select name="role_id" onchange="this.form.submit()">@foreach($roles as $role)<option value="{{ $role->id }}" @selected($user->role_id===$role->id)>{{ $role->label }}</option>@endforeach</select></form></td></tr>@endforeach</tbody></table></div>{{ $users->links() }}</section>
@endsection
