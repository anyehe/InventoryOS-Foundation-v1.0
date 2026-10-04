<?php
namespace App\Http\Controllers\Api\V1;
use App\Models\{Product,Warehouse,WarehouseStock};
use Illuminate\Http\Request;
class InventoryApiController extends ApiController {
 public function index(Request $r){ $rows=WarehouseStock::with(['product','warehouse'])->whereHas('product',fn($q)=>$q->where('is_active',true)); if($r->filled('warehouse_id')) $rows->where('warehouse_id',$r->integer('warehouse_id')); if($r->filled('product_id')) $rows->where('product_id',$r->integer('product_id')); return $this->ok($rows->orderBy('warehouse_id')->paginate(min(max($r->integer('per_page',25),1),config('api.max_page_size')))); }
}
