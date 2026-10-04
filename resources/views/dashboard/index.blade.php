@extends('layouts.app')
@section('content')
<div class="dashboard-head">
    <div><span class="eyebrow">WORKSPACE</span><h1>Operations dashboard</h1><p>Live overview of inventory, sales, purchasing and warehouse activity.</p></div>
    <div class="dashboard-actions">
        <form class="date-filter" method="GET" action="{{ route('dashboard') }}"><input type="date" name="from" value="{{ $from }}"><span>to</span><input type="date" name="to" value="{{ $to }}"><button class="ghost-btn" type="submit">Apply</button></form>
        <a class="primary-btn" href="{{ route('sales.pos') }}"><span>＋</span> New sale</a>
    </div>
</div>

<section class="dash-metrics">
    <article class="dash-card metric-card cyan"><div class="metric-top"><span>Sales</span><span class="metric-icon">₱</span></div><strong>₱{{ number_format($salesTotal,2) }}</strong><small>{{ $ordersCount }} completed orders in selected period</small></article>
    <article class="dash-card metric-card purple"><div class="metric-top"><span>Purchases</span><span class="metric-icon">↙</span></div><strong>₱{{ number_format($purchaseTotal,2) }}</strong><small>{{ $purchaseCount }} purchase records in selected period</small></article>
    <article class="dash-card metric-card green"><div class="metric-top"><span>Gross profit</span><span class="metric-icon">↗</span></div><strong>₱{{ number_format($grossProfit,2) }}</strong><small>Sales less estimated product cost</small></article>
    <article class="dash-card metric-card orange"><div class="metric-top"><span>Inventory value</span><span class="metric-icon">▣</span></div><strong>₱{{ number_format($inventoryValue,2) }}</strong><small>{{ number_format($stockUnits,3) }} units across warehouses</small></article>
</section>

<section class="dashboard-grid main-grid">
    <article class="dash-card sales-chart-card">
        <div class="card-head"><div><h2>Sales performance</h2><span>Daily revenue for the selected period</span></div><a href="{{ route('reports.index') }}">View reports →</a></div>
        <div class="chart-summary"><div><strong>₱{{ number_format($salesTotal,2) }}</strong><small>Total revenue</small></div><div><strong>{{ number_format($ordersCount) }}</strong><small>Orders</small></div><div><strong>₱{{ number_format($costOfGoods,2) }}</strong><small>Estimated COGS</small></div></div>
        <div class="sales-chart" aria-label="Daily sales chart">
            @forelse($chartDays as $day)
                <div class="bar-group" title="{{ $day['day'] }} · ₱{{ number_format($day['total'],2) }} · {{ $day['orders'] }} orders"><div class="bar" style="height:{{ max(4, ($day['total'] / $chartMax) * 100) }}%"></div><span>{{ $day['label'] }}</span></div>
            @empty
                <div class="empty-chart">No sales recorded for this period.</div>
            @endforelse
        </div>
    </article>

    <article class="dash-card period-card">
        <div class="card-head"><div><h2>Business pulse</h2><span>Operational signals</span></div><span class="status-dot">Live</span></div>
        <div class="pulse-total"><span>Net activity</span><strong>₱{{ number_format($salesTotal - $returnsTotal - $expensesTotal,2) }}</strong><small>Sales − returns − expenses</small></div>
        <div class="pulse-row"><span>Returns / refunds</span><strong>₱{{ number_format($returnsTotal,2) }}</strong></div>
        <div class="pulse-row"><span>Operating expenses</span><strong>₱{{ number_format($expensesTotal,2) }}</strong></div>
        <div class="pulse-row"><span>Active customers</span><strong>{{ number_format($customerCount) }}</strong></div>
        <div class="pulse-row"><span>Low-stock records</span><strong class="text-warning">{{ number_format($lowStock) }}</strong></div>
    </article>
</section>

<section class="dashboard-grid lower-grid">
    <article class="dash-card">
        <div class="card-head"><div><h2>Top products</h2><span>Highest revenue in selected period</span></div><a href="{{ route('inventory.products.index') }}">Catalog →</a></div>
        @forelse($topProducts as $product)
            @php $maxRevenue = max(1, (float)($topProducts->max('revenue') ?? 1)); $width = ((float)$product->revenue / $maxRevenue) * 100; @endphp
            <div class="product-row"><div class="product-avatar">{{ strtoupper(substr($product->name,0,1)) }}</div><div class="product-main"><div><strong>{{ $product->name }}</strong><small>{{ $product->sku }} · {{ number_format($product->units,3) }} units</small></div><b>₱{{ number_format($product->revenue,2) }}</b><div class="progress"><span style="width:{{ $width }}%"></span></div></div></div>
        @empty <div class="empty-state">No sales yet for this period. Completed POS sales will appear here.</div> @endforelse
    </article>

    <article class="dash-card">
        <div class="card-head"><div><h2>Inventory health</h2><span>Warehouse valuation</span></div><a href="{{ route('inventory.index') }}">Open inventory →</a></div>
        @forelse($warehouseStats as $warehouse)
            <div class="warehouse-row"><div><strong>{{ $warehouse->name }}</strong><small>{{ number_format($warehouse->units,3) }} units</small></div><b>₱{{ number_format($warehouse->value,2) }}</b></div><div class="progress warehouse-progress"><span style="width:{{ max(5, ((float)$warehouse->value / $warehouseMax) * 100) }}%"></span></div>
        @empty <div class="empty-state">No warehouse stock records yet.</div> @endforelse
        <div class="health-footer"><span>Active products</span><b>{{ $productCount }}</b><span>Low stock</span><b class="text-warning">{{ $lowStock }}</b></div>
    </article>

    <article class="dash-card">
        <div class="card-head"><div><h2>Low-stock watchlist</h2><span>Items at or below reorder level</span></div><a href="{{ route('inventory.products.index') }}">Manage →</a></div>
        @forelse($lowStockItems as $item)
            <div class="compact-row"><div class="row-icon warning">!</div><div><strong>{{ $item->product->name }}</strong><small>{{ $item->warehouse->name }} · reorder {{ number_format($item->product->reorder_level,3) }}</small></div><b>{{ number_format($item->quantity,3) }}</b></div>
        @empty <div class="empty-state success-state">All tracked stock levels are above reorder thresholds.</div> @endforelse
    </article>

    <article class="dash-card movements-card">
        <div class="card-head"><div><h2>Recent movements</h2><span>Latest inventory activity</span></div><a href="{{ route('inventory.movements') }}">View all →</a></div>
        @forelse($movements as $movement)
            <div class="compact-row"><div class="row-icon {{ $movement->quantity < 0 ? 'down' : 'up' }}">{{ $movement->quantity < 0 ? '↓' : '↑' }}</div><div><strong>{{ $movement->product->name }}</strong><small>{{ ucfirst($movement->type) }} · {{ $movement->warehouse->name }} · {{ $movement->created_at->format('M d, H:i') }}</small></div><b class="{{ $movement->quantity < 0 ? 'text-danger' : 'text-success' }}">{{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity,3) }}</b></div>
        @empty <div class="empty-state">No inventory movements yet.</div> @endforelse
    </article>
</section>

<section class="quick-stats">
    <div><span>Total sales</span><strong>₱{{ number_format($salesTotal,2) }}</strong><small>Selected period</small></div>
    <div><span>Total purchases</span><strong>₱{{ number_format($purchaseTotal,2) }}</strong><small>Selected period</small></div>
    <div><span>Total profit</span><strong>₱{{ number_format($grossProfit,2) }}</strong><small>Before operating expenses</small></div>
    <div><span>Total orders</span><strong>{{ number_format($ordersCount) }}</strong><small>Completed sales</small></div>
</section>
@endsection
