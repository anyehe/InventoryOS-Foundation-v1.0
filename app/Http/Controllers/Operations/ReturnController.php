<?php
namespace App\Http\Controllers\Operations;
use App\Http\Controllers\Controller;
use App\Models\{Sale,SaleReturn};
use App\Services\ReturnsService;
use Illuminate\Http\Request;
class ReturnController extends Controller {
 public function index(){ $returns=SaleReturn::with(['sale','user'])->latest('returned_at')->paginate(15); return view('operations.returns.index',compact('returns')); }
 public function create(Sale $sale){$sale->load(['items.product','warehouse']);return view('operations.returns.create',compact('sale'));}
 public function store(Request $r, Sale $sale, ReturnsService $service){$data=$r->validate(['refund_method'=>['required','in:cash,card,bank,gcash,credit'],'refund_reference'=>['nullable','string','max:100'],'disposition'=>['required','in:restock,damaged'],'reason'=>['nullable','string','max:1000'],'items'=>['required','array','min:1','max:100'],'items.*.sale_item_id'=>['required','integer','distinct','exists:sale_items,id'],'items.*.quantity'=>['required','numeric','min:0','max:100000']]);$ret=$service->create($sale,$data['items'],$data['refund_method'],$data['disposition'],$data['refund_reference']??null,$data['reason']??null,$r);return redirect()->route('operations.returns.index')->with('success','Return '.$ret->reference.' completed.');}
}
