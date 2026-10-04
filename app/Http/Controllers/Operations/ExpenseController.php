<?php
namespace App\Http\Controllers\Operations;
use App\Http\Controllers\Controller;
use App\Models\{Expense,ExpenseCategory,Warehouse};
use Illuminate\Http\Request;
class ExpenseController extends Controller {
 public function index(Request $r){$expenses=Expense::with(['category','warehouse'])->latest('expense_date')->paginate(15);$categories=ExpenseCategory::orderBy('name')->get();$warehouses=Warehouse::orderBy('name')->get();return view('operations.expenses.index',compact('expenses','categories','warehouses'));}
 public function store(Request $r){$data=$r->validate(['expense_category_id'=>['required','integer','exists:expense_categories,id'],'warehouse_id'=>['nullable','integer','exists:warehouses,id'],'payee'=>['nullable','string','max:150'],'amount'=>['required','numeric','gt:0','max:999999999'],'payment_method'=>['required','in:cash,card,bank,gcash'],'expense_date'=>['required','date'],'description'=>['nullable','string','max:2000']]);$data['reference']='EXP-'.now()->format('YmdHis').'-'.random_int(100,999);$data['user_id']=$r->user()?->id;$e=Expense::create($data);app(\App\Services\AuditLogger::class)->record($r,'finance.expense_created','success',['reference'=>$e->reference,'amount'=>$e->amount]);return back()->with('success','Expense '.$e->reference.' recorded.');}
 public function storeCategory(Request $r){$data=$r->validate(['name'=>['required','string','max:100','unique:expense_categories,name']]);ExpenseCategory::create($data);return back()->with('success','Expense category created.');}
}
