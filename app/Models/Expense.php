<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Expense extends Model { protected $fillable=['reference','expense_category_id','warehouse_id','user_id','payee','amount','payment_method','status','expense_date','description']; protected $casts=['amount'=>'decimal:2','expense_date'=>'date']; public function category(){return $this->belongsTo(ExpenseCategory::class,'expense_category_id');} public function warehouse(){return $this->belongsTo(Warehouse::class);} public function user(){return $this->belongsTo(User::class);} }
