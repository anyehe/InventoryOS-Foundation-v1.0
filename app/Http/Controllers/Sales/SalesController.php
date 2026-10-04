<?php
namespace App\Http\Controllers\Sales;
use App\Http\Controllers\Controller;
use App\Models\{Product,Sale,Warehouse,Customer};
use App\Services\SalesService;
use Illuminate\Http\Request;
class SalesController extends Controller {
 public function pos(){return view('sales.pos',['warehouses'=>Warehouse::orderBy('name')->get(),'customers'=>Customer::where('is_active',true)->orderBy('name')->get(['id','name','code']),'products'=>Product::where('is_active',true)->orderBy('name')->get(['id','name','sku','selling_price','reorder_level'])]);}
 public function store(Request $r,SalesService $sales){$data=$r->validate(['warehouse_id'=>['required','integer','exists:warehouses,id'],'customer_id'=>['nullable','integer','exists:customers,id'],'customer_name'=>['nullable','string','max:150'],'discount'=>['nullable','numeric','min:0'],'tax'=>['nullable','numeric','min:0'],'payment_method'=>['required','in:cash,card,bank,gcash'],'paid'=>['required','numeric','min:0'],'note'=>['nullable','string','max:1000'],'items'=>['required','array','min:1','max:100'],'items.*.product_id'=>['required','integer','distinct','exists:products,id'],'items.*.quantity'=>['required','numeric','gt:0','max:100000']]);$sale=$sales->createSale(Warehouse::findOrFail($data['warehouse_id']),$data['items'],$data['discount']??0,$data['tax']??0,$data['payment_method'],$data['paid'],$data['customer_name']??null,$data['customer_id']??null,$data['note']??null,$r);return redirect()->route('sales.show',$sale)->with('success','Sale completed: '.$sale->invoice_no);}
 public function index(){ $sales=Sale::with(['warehouse','customer'])->latest('sold_at')->paginate(15);return view('sales.index',compact('sales')); }
 public function show(Sale $sale){$sale->load(['items.product','payments','warehouse','user','customer']);return view('sales.show',compact('sale'));}
}
