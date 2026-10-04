<?php
namespace App\Http\Controllers\Api\V1;
use App\Models\{Sale,Purchase,WarehouseStock};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class ReportApiController extends ApiController {
 public function summary(Request $r){ $from=$r->date('from')?->startOfDay() ?? now()->subDays(29)->startOfDay(); $to=$r->date('to')?->endOfDay() ?? now()->endOfDay(); if($from->gt($to)) return $this->fail('The from date must not be after the to date.'); $sales=Sale::whereBetween('created_at',[$from,$to])->where('status','completed'); $purchases=Purchase::whereBetween('created_at',[$from,$to])->whereIn('status',['ordered','partial','received']); return $this->ok(['from'=>$from->toDateString(),'to'=>$to->toDateString(),'sales_total'=>(float)$sales->sum('total'),'sales_count'=>$sales->count(),'purchase_total'=>(float)$purchases->sum('total'),'purchase_count'=>$purchases->count(),'inventory_value'=>(float)WarehouseStock::query()->join('products','products.id','=','warehouse_stocks.product_id')->selectRaw('COALESCE(SUM(warehouse_stocks.quantity * products.cost_price),0) as value')->value('value')]); }
}
