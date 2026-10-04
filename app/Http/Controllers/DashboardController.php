<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Customer, Expense, Product, Purchase, Sale, SaleReturn, StockMovement, WarehouseStock};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();

        $salesQuery = Sale::query()->whereBetween('sold_at', [$fromDate, $toDate]);
        $purchasesQuery = Purchase::query()->whereBetween('created_at', [$fromDate, $toDate])
            ->whereIn('status', ['ordered', 'partial', 'received']);

        $salesTotal = (float) $salesQuery->sum('total');
        $ordersCount = (int) $salesQuery->count();
        $purchaseTotal = (float) $purchasesQuery->sum('total');
        $purchaseCount = (int) $purchasesQuery->count();
        $returnsTotal = (float) SaleReturn::whereBetween('returned_at', [$fromDate, $toDate])->sum('refund_amount');
        $expensesTotal = (float) Expense::whereBetween('expense_date', [$fromDate->toDateString(), $toDate->toDateString()])->sum('amount');

        $costOfGoods = (float) DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.sold_at', [$fromDate, $toDate])
            ->sum(DB::raw('sale_items.quantity * products.cost_price'));
        $grossProfit = $salesTotal - $costOfGoods;

        $inventoryValue = (float) DB::table('warehouse_stocks')
            ->join('products', 'products.id', '=', 'warehouse_stocks.product_id')
            ->sum(DB::raw('warehouse_stocks.quantity * products.cost_price'));
        $stockUnits = (float) WarehouseStock::sum('quantity');
        $productCount = (int) Product::where('is_active', true)->count();
        $lowStock = (int) WarehouseStock::whereHas('product', fn ($q) => $q->whereColumn('warehouse_stocks.quantity', '<=', 'products.reorder_level'))->count();
        $customerCount = (int) Customer::where('is_active', true)->count();

        $dailySales = DB::table('sales')
            ->whereBetween('sold_at', [$fromDate, $toDate])
            ->select(DB::raw('DATE(sold_at) as day'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as orders'))
            ->groupBy('day')->orderBy('day')->get()->keyBy('day');

        $chartDays = [];
        $cursor = $fromDate->copy();
        $maxDays = min(31, $fromDate->diffInDays($toDate) + 1);
        for ($i = 0; $i < $maxDays; $i++) {
            $key = $cursor->toDateString();
            $row = $dailySales->get($key);
            $chartDays[] = [
                'label' => $cursor->format($maxDays > 14 ? 'd' : 'M d'),
                'day' => $key,
                'total' => (float) ($row->total ?? 0),
                'orders' => (int) ($row->orders ?? 0),
            ];
            $cursor->addDay();
        }
        $chartMax = max(1, max(array_column($chartDays, 'total')));

        $topProducts = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.sold_at', [$fromDate, $toDate])
            ->select('products.name', 'products.sku', DB::raw('SUM(sale_items.quantity) as units'), DB::raw('SUM(sale_items.line_total) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('revenue')->limit(5)->get();

        $warehouseStats = DB::table('warehouse_stocks')
            ->join('warehouses', 'warehouses.id', '=', 'warehouse_stocks.warehouse_id')
            ->join('products', 'products.id', '=', 'warehouse_stocks.product_id')
            ->select('warehouses.name', DB::raw('SUM(warehouse_stocks.quantity) as units'), DB::raw('SUM(warehouse_stocks.quantity * products.cost_price) as value'))
            ->groupBy('warehouses.id', 'warehouses.name')->orderByDesc('value')->get();

        $warehouseMax = max(1, (float) ($warehouseStats->max('value') ?? 0));
        $lowStockItems = WarehouseStock::with(['product', 'warehouse'])
            ->whereHas('product', fn ($q) => $q->whereColumn('warehouse_stocks.quantity', '<=', 'products.reorder_level'))
            ->orderBy('quantity')->limit(5)->get();

        $movements = StockMovement::with(['product', 'warehouse'])->latest()->limit(6)->get();

        return view('dashboard.index', compact(
            'from', 'to', 'salesTotal', 'ordersCount', 'purchaseTotal', 'purchaseCount', 'returnsTotal', 'expensesTotal',
            'costOfGoods', 'grossProfit', 'inventoryValue', 'stockUnits', 'productCount', 'lowStock', 'customerCount',
            'chartDays', 'chartMax', 'topProducts', 'warehouseStats', 'warehouseMax', 'lowStockItems', 'movements'
        ));
    }
}
