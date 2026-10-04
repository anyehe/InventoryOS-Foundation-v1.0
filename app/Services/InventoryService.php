<?php
namespace App\Services;
use App\Models\{Product,Warehouse,WarehouseStock,StockMovement,StockTransfer};
use Illuminate\Database\DatabaseManager; use Illuminate\Validation\ValidationException;
class InventoryService {
 public function __construct(private DatabaseManager $db, private AuditLogger $audit) {}
 public function adjust(Product $product, Warehouse $warehouse, float $quantity, string $type, ?string $reference, ?string $note, $request): WarehouseStock {
  return $this->db->transaction(function() use($product,$warehouse,$quantity,$type,$reference,$note,$request){
   $stock=WarehouseStock::where('product_id',$product->id)->where('warehouse_id',$warehouse->id)->lockForUpdate()->first();
   if(!$stock) $stock=WarehouseStock::create(['product_id'=>$product->id,'warehouse_id'=>$warehouse->id,'quantity'=>0]);
   $new=(float)$stock->quantity+$quantity;
   if($new < 0) throw ValidationException::withMessages(['quantity'=>'Insufficient stock for this adjustment.']);
   $stock->update(['quantity'=>$new]);
   StockMovement::create(['product_id'=>$product->id,'warehouse_id'=>$warehouse->id,'user_id'=>$request->user()?->id,'type'=>$type,'quantity'=>$quantity,'balance_after'=>$new,'reference'=>$reference,'note'=>$note]);
   $this->audit->record($request,'inventory.stock_adjusted','success',['product_id'=>$product->id,'warehouse_id'=>$warehouse->id,'delta'=>$quantity,'balance'=>$new]);
   return $stock->fresh();
  });
 }
 public function transfer(Product $product, Warehouse $from, Warehouse $to, float $quantity, ?string $note, $request): StockTransfer {
  if($from->id === $to->id) throw ValidationException::withMessages(['to_warehouse_id'=>'Source and destination warehouses must be different.']);
  return $this->db->transaction(function() use($product,$from,$to,$quantity,$note,$request){
   $src=WarehouseStock::where('product_id',$product->id)->where('warehouse_id',$from->id)->lockForUpdate()->first();
   if(!$src || (float)$src->quantity < $quantity) throw ValidationException::withMessages(['quantity'=>'Insufficient stock in the source warehouse.']);
   $dst=WarehouseStock::where('product_id',$product->id)->where('warehouse_id',$to->id)->lockForUpdate()->first();
   if(!$dst) $dst=WarehouseStock::create(['product_id'=>$product->id,'warehouse_id'=>$to->id,'quantity'=>0]);
   $src->quantity=(float)$src->quantity-$quantity; $dst->quantity=(float)$dst->quantity+$quantity; $src->save(); $dst->save();
   $ref='TRF-'.now()->format('YmdHis').'-'.random_int(100,999);
   $transfer=StockTransfer::create(['reference'=>$ref,'from_warehouse_id'=>$from->id,'to_warehouse_id'=>$to->id,'product_id'=>$product->id,'quantity'=>$quantity,'status'=>'completed','user_id'=>$request->user()?->id,'note'=>$note]);
   StockMovement::create(['product_id'=>$product->id,'warehouse_id'=>$from->id,'user_id'=>$request->user()?->id,'type'=>'transfer_out','quantity'=>-$quantity,'balance_after'=>$src->quantity,'reference'=>$ref,'note'=>$note]);
   StockMovement::create(['product_id'=>$product->id,'warehouse_id'=>$to->id,'user_id'=>$request->user()?->id,'type'=>'transfer_in','quantity'=>$quantity,'balance_after'=>$dst->quantity,'reference'=>$ref,'note'=>$note]);
   $this->audit->record($request,'inventory.stock_transferred','success',['reference'=>$ref,'product_id'=>$product->id,'from'=>$from->id,'to'=>$to->id,'quantity'=>$quantity]);
   return $transfer;
  });
 }
}
