@extends('layouts.app')
@section('content')
<div class="page-head">
    <div>
        <span class="eyebrow">ANALYTICS</span>
        <h1>Reports &amp; analytics</h1>
        <p>Sales, profitability, inventory value and operating signals from InventoryOS.</p>
    </div>
    <a class="btn primary" href="{{ route('reports.sales.csv', ['from'=>$from,'to'=>$to]) }}">Export sales CSV</a>
</div>

<form class="panel filter-bar" method="GET" action="{{ route('reports.index') }}">
    <div><label>From<input type="date" name="from" value="{{ $from }}"></label></div>
    <div><label>To<input type="date" name="to" value="{{ $to }}"></label></div>
    <div class="filter-action"><button class="btn primary" type="submit">Apply period</button></div>
</form>

<section class="report-kpis">
    <article class="report-kpi kpi-cyan">
        <div class="kpi-icon">₱</div>
        <div class="kpi-copy"><span>Net sales</span><strong>₱{{ number_format($netSales,2) }}</strong><small>{{ number_format($salesCount) }} completed orders · after discounts, before tax</small></div>
    </article>
    <article class="report-kpi kpi-green">
        <div class="kpi-icon">↗</div>
        <div class="kpi-copy"><span>Gross profit</span><strong>₱{{ number_format($grossProfit,2) }}</strong><small>{{ number_format($grossMargin,1) }}% gross margin · net sales less estimated COGS</small></div>
    </article>
    <article class="report-kpi kpi-purple">
        <div class="kpi-icon">▤</div>
        <div class="kpi-copy"><span>Estimated COGS</span><strong>₱{{ number_format($costOfGoods,2) }}</strong><small>Estimated product cost for sales in this period</small></div>
    </article>
    <article class="report-kpi kpi-orange">
        <div class="kpi-icon">−</div>
        <div class="kpi-copy"><span>Operating expenses</span><strong>₱{{ number_format($expensesTotal,2) }}</strong><small>Recorded operating expenses in this period</small></div>
    </article>
</section>

<section class="report-secondary-grid">
    <article class="panel report-breakdown">
        <div class="panel-head"><div><h2>Financial breakdown</h2><span>How the selected sales period is composed</span></div></div>
        <div class="financial-rows">
            <div><span>Gross sales</span><b>₱{{ number_format($salesTotal + $salesDiscount - $salesTax,2) }}</b></div>
            <div><span>Discounts</span><b>− ₱{{ number_format($salesDiscount,2) }}</b></div>
            <div class="emphasis"><span>Net sales</span><b>₱{{ number_format($netSales,2) }}</b></div>
            <div><span>Sales tax collected</span><b>₱{{ number_format($salesTax,2) }}</b></div>
            <div><span>Estimated COGS</span><b>− ₱{{ number_format($costOfGoods,2) }}</b></div>
            <div class="emphasis profit"><span>Gross profit</span><b>₱{{ number_format($grossProfit,2) }}</b></div>
            <div><span>Operating expenses</span><b>− ₱{{ number_format($expensesTotal,2) }}</b></div>
            <div class="emphasis"><span>Operating profit <small>(before return-cost adjustments)</small></span><b>₱{{ number_format($operatingProfitBeforeReturns,2) }}</b></div>
        </div>
    </article>
    <article class="panel report-context">
        <div class="panel-head"><div><h2>Operational context</h2><span>Other signals for the selected period</span></div></div>
        <div class="context-grid">
            <div><span>Purchases</span><strong>₱{{ number_format($purchaseTotal,2) }}</strong><small>{{ number_format($purchaseCount) }} purchase records</small></div>
            <div><span>Returns / refunds</span><strong>₱{{ number_format($returnsTotal,2) }}</strong><small>Refunds recorded in this period</small></div>
            <div><span>Inventory value</span><strong>₱{{ number_format($inventoryValue,2) }}</strong><small>Current stock × cost price</small></div>
            <div><span>Active customers</span><strong>{{ number_format($customerCount) }}</strong><small>Active customer profiles</small></div>
        </div>
    </article>
</section>

<section class="analytics-grid">
    <article class="panel"><div class="panel-head"><div><h2>Daily sales</h2><span>{{ $from }} → {{ $to }}</span></div></div><div class="chart-bars">@forelse($dailySales as $day)<div class="bar-col"><div class="bar" style="height:{{ max(8, min(100, ($day->total / max(1, $dailySales->max('total'))) * 100)) }}%" title="₱{{ number_format($day->total,2) }}"></div><small>{{ \Carbon\Carbon::parse($day->day)->format('M d') }}</small></div>@empty<div class="empty">No sales in this period.</div>@endforelse</div></article>
    <article class="panel"><div class="panel-head"><div><h2>Sales mix</h2><span>Revenue indicators</span></div></div><div class="stat-list"><div><span>Gross sales before discounts</span><b>₱{{ number_format($salesTotal + $salesDiscount - $salesTax,2) }}</b></div><div><span>Discounts</span><b>₱{{ number_format($salesDiscount,2) }}</b></div><div><span>Tax collected</span><b>₱{{ number_format($salesTax,2) }}</b></div><div><span>Estimated cost</span><b>₱{{ number_format($costOfGoods,2) }}</b></div></div></article>
</section>

<section class="analytics-grid">
    <article class="panel"><div class="panel-head"><div><h2>Top products</h2><span>By sales revenue</span></div></div><div class="table-wrap"><table><thead><tr><th>Product</th><th>Units</th><th class="right">Revenue</th></tr></thead><tbody>@forelse($topProducts as $item)<tr><td><b>{{ $item->name }}</b><small>{{ $item->sku }}</small></td><td>{{ number_format($item->units,3) }}</td><td class="right">₱{{ number_format($item->revenue,2) }}</td></tr>@empty<tr><td colspan="3" class="empty">No product sales in this period.</td></tr>@endforelse</tbody></table></div></article>
    <article class="panel"><div class="panel-head"><div><h2>Warehouse stock</h2><span>Current valuation</span></div></div><div class="table-wrap"><table><thead><tr><th>Warehouse</th><th>Units</th><th class="right">Value</th></tr></thead><tbody>@foreach($warehouseStock as $row)<tr><td>{{ $row->warehouse->name }}</td><td>{{ number_format($row->units,3) }}</td><td class="right">₱{{ number_format($row->value,2) }}</td></tr>@endforeach</tbody></table></div></article>
</section>

<section class="analytics-grid">
    <article class="panel"><div class="panel-head"><div><h2>Low-stock watchlist</h2><span>At or below reorder level</span></div><a href="{{ route('inventory.index') }}">Open inventory →</a></div><div class="table-wrap"><table><thead><tr><th>Product</th><th>Warehouse</th><th>On hand</th><th>Reorder</th></tr></thead><tbody>@forelse($lowStock as $stock)<tr><td>{{ $stock->product->name }}<small>{{ $stock->product->sku }}</small></td><td>{{ $stock->warehouse->name }}</td><td><span class="badge danger">{{ number_format($stock->quantity,3) }}</span></td><td>{{ number_format($stock->product->reorder_level,3) }}</td></tr>@empty<tr><td colspan="4" class="empty">No low-stock records.</td></tr>@endforelse</tbody></table></div></article>
    <article class="panel"><div class="panel-head"><div><h2>Stock movement activity</h2><span>Selected reporting period</span></div></div><div class="stat-list">@forelse($movementSummary as $row)<div><span>{{ ucfirst(str_replace('_',' ',$row->type)) }}</span><b>{{ number_format($row->events) }} events · {{ number_format($row->quantity,3) }}</b></div>@empty<div class="empty">No movements in this period.</div>@endforelse</div></article>
</section>
@endsection
