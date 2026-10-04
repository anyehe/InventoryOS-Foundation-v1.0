<?php
namespace App\Http\Controllers\Api\V1;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductApiController extends ApiController {
 public function index(Request $r){ $per=min(max((int)$r->integer('per_page',25),1),config('api.max_page_size')); $q=Product::with(['category','brand','unit','stocks'])->where('is_active',true); if($r->filled('search')){ $term=trim($r->string('search')); $q->where(fn($x)=>$x->where('name','like','%'.$term.'%')->orWhere('sku','like','%'.$term.'%')); } $p=$q->orderBy('name')->paginate($per); return $this->ok($p->items(),['pagination'=>['current_page'=>$p->currentPage(),'per_page'=>$p->perPage(),'total'=>$p->total(),'last_page'=>$p->lastPage()]]); }
 public function show(Product $product){ return $this->ok($product->load(['category','brand','unit','stocks.warehouse'])); }
}
