<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SaleReturnItem extends Model { protected $fillable=['sale_return_id','sale_item_id','product_id','quantity','unit_price','line_total']; protected $casts=['quantity'=>'decimal:3','unit_price'=>'decimal:2','line_total'=>'decimal:2']; public function return(){return $this->belongsTo(SaleReturn::class,'sale_return_id');} public function saleItem(){return $this->belongsTo(SaleItem::class);} public function product(){return $this->belongsTo(Product::class);} }
