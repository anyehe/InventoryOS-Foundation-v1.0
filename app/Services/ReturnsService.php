<?php
namespace App\Services;
use App\Models\{Sale,SaleItem,SaleReturn,SaleReturnItem,WarehouseStock,StockMovement};
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
class ReturnsService {
 public function __construct(private DatabaseManager $db, private AuditLogger $audit) {}
 public function create(Sale $sale, array $items, string $refundMethod, string $disposition, ?string $refundReference, ?string $reason, $request): SaleReturn {
  return $this->db->transaction(function() use($sale,$items,$refundMethod,$disposition,$refundReference,$reason,$request){
   $sale=$sale->newQuery()->lockForUpdate()->with('items')->findOrFail($sale->id);
   if($sale->status !== 'completed') throw ValidationException::withMessages(['sale'=>'Only completed sales can be returned.']);
   $returnable=[]; $refund=0;
   foreach($items as $row){
    $saleItem=SaleItem::where('id',$row['sale_item_id'])->where('sale_id',$sale->id)->lockForUpdate()->firstOrFail();
    $already=(float)SaleReturnItem::where('sale_item_id',$saleItem->id)->sum('quantity');
    $remaining=(float)$saleItem->quantity-$already; $qty=(float)$row['quantity'];
    if($qty<=0) continue;
    if($qty>$remaining) throw ValidationException::withMessages(['items'=>'Return quantity exceeds the remaining returnable quantity for one or more items.']);
    $line=round($qty*(float)$saleItem->unit_price,2); $refund+= $line; $returnable[] = [$saleItem,$qty,$line];
   }
   if(!$returnable) throw ValidationException::withMessages(['items'=>'At least one item is required.']);
   $ref='RET-'.now()->format('YmdHis').'-'.random_int(100,999);
   $ret=SaleReturn::create(['reference'=>$ref,'sale_id'=>$sale->id,'warehouse_id'=>$sale->warehouse_id,'user_id'=>$request->user()?->id,'status'=>'completed','refund_method'=>$refundMethod,'refund_amount'=>$refund,'refund_reference'=>$refundReference,'disposition'=>$disposition,'reason'=>$reason]);
   foreach($returnable as [$saleItem,$qty,$line]){
    SaleReturnItem::create(['sale_return_id'=>$ret->id,'sale_item_id'=>$saleItem->id,'product_id'=>$saleItem->product_id,'quantity'=>$qty,'unit_price'=>$saleItem->unit_price,'line_total'=>$line]);
    if($disposition==='restock'){
      $stock=WarehouseStock::where('warehouse_id',$sale->warehouse_id)->where('product_id',$saleItem->product_id)->lockForUpdate()->first();
      if(!$stock) $stock=WarehouseStock::create(['warehouse_id'=>$sale->warehouse_id,'product_id'=>$saleItem->product_id,'quantity'=>0]);
      $stock->quantity=(float)$stock->quantity+$qty; $stock->save();
      StockMovement::create(['product_id'=>$saleItem->product_id,'warehouse_id'=>$sale->warehouse_id,'user_id'=>$request->user()?->id,'type'=>'sale_return','quantity'=>$qty,'balance_after'=>$stock->quantity,'reference'=>$ref,'note'=>$reason]);
    }
   }
   $this->audit->record($request,'sales.return_created','success',['reference'=>$ref,'sale_id'=>$sale->id,'refund_amount'=>$refund,'disposition'=>$disposition]);
   return $ret->load(['items.product','sale']);
  });
 }
}
