<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sale extends Model { protected $fillable=['invoice_no','warehouse_id','customer_id','user_id','customer_name','subtotal','discount','tax','total','status','sold_at','note']; protected $casts=['subtotal'=>'decimal:2','discount'=>'decimal:2','tax'=>'decimal:2','total'=>'decimal:2','sold_at'=>'datetime']; public function items(){return $this->hasMany(SaleItem::class);} public function payments(){return $this->hasMany(Payment::class);} public function customer(){return $this->belongsTo(Customer::class);}
 public function warehouse(){return $this->belongsTo(Warehouse::class);} public function user(){return $this->belongsTo(User::class);} }
