<?php
namespace App\Http\Controllers\Customers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
class CustomerController extends Controller {
 public function index(Request $r){$q=trim((string)$r->input('q',''));$customers=Customer::when($q,fn($x)=>$x->where(fn($w)=>$w->where('name','like','%'.$q.'%')->orWhere('code','like','%'.$q.'%')->orWhere('email','like','%'.$q.'%')))->latest()->paginate(15)->withQueryString();return view('customers.index',compact('customers','q'));}
 public function store(Request $r, AuditLogger $audit){$data=$r->validate(['name'=>['required','string','max:150'],'code'=>['required','string','max:40','alpha_dash','unique:customers,code'],'email'=>['nullable','email','max:190'],'phone'=>['nullable','string','max:40'],'address'=>['nullable','string','max:2000'],'credit_limit'=>['nullable','numeric','min:0','max:999999999'],'is_active'=>['nullable','boolean']]);$data['is_active']=$r->boolean('is_active');$c=Customer::create($data);$audit->record($r,'customers.created','success',['customer_id'=>$c->id,'code'=>$c->code]);return back()->with('success','Customer '.$c->code.' created.');}
 public function update(Request $r,Customer $customer, AuditLogger $audit){$data=$r->validate(['name'=>['required','string','max:150'],'email'=>['nullable','email','max:190'],'phone'=>['nullable','string','max:40'],'address'=>['nullable','string','max:2000'],'credit_limit'=>['nullable','numeric','min:0','max:999999999'],'is_active'=>['nullable','boolean']]);$data['is_active']=$r->boolean('is_active');$customer->update($data);$audit->record($r,'customers.updated','success',['customer_id'=>$customer->id]);return back()->with('success','Customer updated.');}
}
