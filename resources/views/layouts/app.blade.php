<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'InventoryOS' }} · InventoryOS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="ios-body">
<div class="app-shell" id="appShell">
    <aside class="sidebar" id="sidebar">
        <div class="brand-row">
            <a class="brand" href="{{ route('dashboard') }}" aria-label="InventoryOS dashboard">
                <span class="brand-logo" aria-hidden="true">
                    <svg viewBox="0 0 40 40" fill="none"><path d="M20 3 35 11.5v17L20 37 5 28.5v-17L20 3Z" stroke="currentColor" stroke-width="2.3"/><path d="m8 12 12 7 12-7M20 19v15" stroke="currentColor" stroke-width="2.3" stroke-linejoin="round"/><path d="m14 9 12 7-6 3-12-7 6-3Z" fill="currentColor" opacity=".45"/></svg>
                </span>
                <span class="brand-copy"><strong>Inventory<span>OS</span></strong><small>Smart inventory · smarter business</small></span>
            </a>
            <button class="sidebar-toggle desktop-toggle" id="sidebarToggle" type="button" aria-label="Collapse navigation">☰</button>
        </div>

        <div class="sidebar-search"><span>⌕</span><input type="search" placeholder="Search..." id="navSearch"></div>
        <nav class="nav-groups" aria-label="Primary navigation">
            <span class="nav-label">Workspace</span>
            @php
                $links = [
                    ['route'=>'dashboard','label'=>'Dashboard','icon'=>'grid'],
                    ['route'=>'inventory.index','label'=>'Inventory','icon'=>'box'],
                    ['route'=>'inventory.products.index','label'=>'Products','icon'=>'cube'],
                    ['route'=>'inventory.movements','label'=>'Stock Movements','icon'=>'move'],
                    ['route'=>'inventory.warehouses','label'=>'Warehouses','icon'=>'warehouse'],
                    ['route'=>'sales.index','label'=>'Sales','icon'=>'sales'],
                    ['route'=>'sales.pos','label'=>'POS','icon'=>'pos'],
                    ['route'=>'purchases.index','label'=>'Purchasing','icon'=>'cart'],
                    ['route'=>'purchases.suppliers.index','label'=>'Suppliers','icon'=>'users'],
                    ['route'=>'reports.index','label'=>'Reports','icon'=>'chart'],
                    ['route'=>'customers.index','label'=>'Customers','icon'=>'customer'],
                    ['route'=>'operations.returns.index','label'=>'Returns','icon'=>'return'],
                    ['route'=>'operations.expenses.index','label'=>'Expenses','icon'=>'wallet'],
                ];
            @endphp
            @foreach($links as $link)
                @php $active = request()->routeIs($link['route']) || ($link['route'] === 'inventory.index' && request()->routeIs('inventory.*') && !request()->routeIs('inventory.products.*') && !request()->routeIs('inventory.movements')); @endphp
                <a class="nav-item {{ $active ? 'active' : '' }}" href="{{ route($link['route']) }}" title="{{ $link['label'] }}" data-nav-label="{{ strtolower($link['label']) }}">
                    <span class="nav-icon">@include('layouts.icon', ['name'=>$link['icon']])</span><span class="nav-text">{{ $link['label'] }}</span>
                </a>
            @endforeach
            @if(auth()->user()->hasRole('admin'))
                <span class="nav-label admin-label">Administration</span>
                @foreach([
                    ['route'=>'admin.users.index','label'=>'Users & Roles','icon'=>'users'],
                    ['route'=>'admin.api-keys.index','label'=>'API Keys','icon'=>'key'],
                    ['route'=>'admin.audit.index','label'=>'Audit Log','icon'=>'audit'],
                ] as $link)
                    <a class="nav-item {{ request()->routeIs($link['route']) ? 'active' : '' }}" href="{{ route($link['route']) }}" title="{{ $link['label'] }}" data-nav-label="{{ strtolower($link['label']) }}"><span class="nav-icon">@include('layouts.icon', ['name'=>$link['icon']])</span><span class="nav-text">{{ $link['label'] }}</span></a>
                @endforeach
            @endif
        </nav>

        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="sidebar-user-copy"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->role === 'admin' ? 'System Administrator' : ucfirst(auth()->user()->role) }}</small></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-icon" title="Sign out" aria-label="Sign out">↪</button></form>
        </div>
    </aside>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <main class="main">
        <header class="topbar">
            <button class="mobile-menu" id="mobileMenu" type="button" aria-label="Open navigation">☰</button>
            <div class="global-search"><span>⌕</span><input type="search" placeholder="Search anything..." aria-label="Search anything"><kbd>Ctrl K</kbd></div>
            <div class="top-actions">
                <button class="top-icon" type="button" aria-label="Notifications"><svg viewBox="0 0 24 24"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span class="notification-dot"></span></button>
                <div class="top-user"><div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->role === 'admin' ? 'System Administrator' : ucfirst(auth()->user()->role) }}</small></div><span class="chevron">⌄</span></div>
            </div>
        </header>
        <div class="content">
            @if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="notice error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </div>
    </main>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
