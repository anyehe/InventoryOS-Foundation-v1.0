<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\{Product, Sale, Purchase, StockMovement, WarehouseStock, SaleReturn, Expense, Customer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->dateRange($request);

        $sales = Sale::whereBetween('sold_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
        $purchases = Purchase::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);

        $salesTotal = (float) $sales->sum('total');
        $salesDiscount = (float) $sales->sum('discount');
        $salesTax = (float) $sales->sum('tax');
        $salesCount = (int) $sales->count();
        $purchaseTotal = (float) $purchases->whereIn('status', ['ordered', 'partial', 'received'])->sum('total');
        $purchaseCount = (int) $purchases->whereIn('status', ['ordered', 'partial', 'received'])->count();
        $returnsTotal = (float) SaleReturn::whereBetween('returned_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->sum('refund_amount');
        $expensesTotal = (float) Expense::whereBetween('expense_date', [$from, $to])->sum('amount');
        $customerCount = (int) Customer::where('is_active', true)->count();

        $costOfGoods = (float) DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.sold_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum(DB::raw('sale_items.quantity * products.cost_price'));

        // Financial reporting uses revenue before sales tax. Tax collected is a liability, not operating revenue.
        $netSales = max(0, $salesTotal - $salesTax);
        $netSalesBeforeTax = max(0, $salesTotal - $salesTax);
        $grossProfit = $netSalesBeforeTax - $costOfGoods;
        $grossMargin = $netSalesBeforeTax > 0 ? ($grossProfit / $netSalesBeforeTax) * 100 : 0;
        $operatingProfitBeforeReturns = $grossProfit - $expensesTotal;
        $inventoryValue = (float) DB::table('warehouse_stocks')
            ->join('products', 'products.id', '=', 'warehouse_stocks.product_id')
            ->sum(DB::raw('warehouse_stocks.quantity * products.cost_price'));

        $topProducts = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.sold_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->select('products.name', 'products.sku', DB::raw('SUM(sale_items.quantity) as units'), DB::raw('SUM(sale_items.line_total) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('revenue')->limit(8)->get();

        $dailySales = DB::table('sales')
            ->whereBetween('sold_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->select(DB::raw('DATE(sold_at) as day'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as orders'))
            ->groupBy('day')->orderBy('day')->get();

        $warehouseStock = WarehouseStock::with('warehouse', 'product')
            ->select('warehouse_id', DB::raw('SUM(quantity) as units'), DB::raw('SUM(quantity * (SELECT cost_price FROM products WHERE products.id = warehouse_stocks.product_id)) as value'))
            ->groupBy('warehouse_id')->get();

        $lowStock = WarehouseStock::with(['product', 'warehouse'])
            ->whereHas('product', fn ($q) => $q->whereColumn('warehouse_stocks.quantity', '<=', 'products.reorder_level'))
            ->orderBy('quantity')->limit(12)->get();

        $movementSummary = StockMovement::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->select('type', DB::raw('SUM(quantity) as quantity'), DB::raw('COUNT(*) as events'))
            ->groupBy('type')->orderByDesc('events')->get();

        return view('reports.index', compact(
            'from', 'to', 'salesTotal', 'salesDiscount', 'salesTax', 'salesCount',
            'purchaseTotal', 'purchaseCount', 'returnsTotal', 'expensesTotal', 'customerCount', 'costOfGoods', 'grossProfit',
            'inventoryValue', 'netSales', 'grossMargin', 'operatingProfitBeforeReturns', 'topProducts', 'dailySales', 'warehouseStock',
            'lowStock', 'movementSummary'
        ));
    }

    public function salesCsv(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $rows = Sale::with('warehouse')->whereBetween('sold_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->latest('sold_at')->get();
        $name = 'sales-' . $from . '-to-' . $to . '.csv';
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Invoice', 'Date', 'Warehouse', 'Customer', 'Subtotal', 'Discount', 'Tax', 'Total', 'Status']);
            foreach ($rows as $sale) {
                fputcsv($out, [$sale->invoice_no, optional($sale->sold_at)->format('Y-m-d H:i'), $sale->warehouse->name, $sale->customer_name, $sale->subtotal, $sale->discount, $sale->tax, $sale->total, $sale->status]);
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function dateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        return [$from, $to];
    }
}
