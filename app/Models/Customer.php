<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model { protected $fillable=['name','code','email','phone','address','credit_limit','is_active']; protected $casts=['credit_limit'=>'decimal:2','is_active'=>'boolean']; public function sales(){return $this->hasMany(Sale::class);} }
