<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SaleReturn extends Model { protected $fillable=['reference','sale_id','warehouse_id','user_id','status','refund_method','refund_amount','refund_reference','disposition','reason','returned_at']; protected $casts=['refund_amount'=>'decimal:2','returned_at'=>'datetime']; public function sale(){return $this->belongsTo(Sale::class);} public function warehouse(){return $this->belongsTo(Warehouse::class);} public function user(){return $this->belongsTo(User::class);} public function items(){return $this->hasMany(SaleReturnItem::class);} }
