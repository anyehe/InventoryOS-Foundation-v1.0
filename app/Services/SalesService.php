<?php
namespace App\Services;
use App\Models\{Payment,Product,Sale,SaleItem,StockMovement,Warehouse,WarehouseStock};
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
class SalesService {
 public function __construct(private DatabaseManager $db, private AuditLogger $audit) {}
 public function createSale(Warehouse $warehouse,array $items,float $discount,float $tax,string $paymentMethod,float $paid,?string $customerName,?int $customerId,?string $note,$request): Sale {
  return $this->db->transaction(function() use($warehouse,$items,$discount,$tax,$paymentMethod,$paid,$customerName,$customerId,$note,$request){
   $clean=[];$subtotal=0;
   foreach($items as $row){$product=Product::whereKey((int)$row['product_id'])->where('is_active',true)->lockForUpdate()->first(); if(!$product) throw ValidationException::withMessages(['items'=>'One or more products are unavailable.']); $qty=(float)$row['quantity']; if($qty<=0) throw ValidationException::withMessages(['items'=>'Quantity must be greater than zero.']); $stock=WarehouseStock::where('product_id',$product->id)->where('warehouse_id',$warehouse->id)->lockForUpdate()->first(); $available=(float)($stock?->quantity ?? 0); if($available<$qty) throw ValidationException::withMessages(['items'=>"Insufficient stock for {$product->name}. Available: {$available}."]); $unit=(float)$product->selling_price; $line=round($unit*$qty,2); $clean[]=['product'=>$product,'stock'=>$stock,'quantity'=>$qty,'unit_price'=>$unit,'line_total'=>$line]; $subtotal+=$line; }
   $discount=max(0,$discount);$tax=max(0,$tax);$total=round($subtotal-$discount+$tax,2);if($total<0)throw ValidationException::withMessages(['discount'=>'Discount cannot exceed the sale subtotal.']);if($paid+0.0001<$total)throw ValidationException::withMessages(['paid'=>"Payment is insufficient. Required: {$total}."]);$invoice='INV-'.now()->format('YmdHis').'-'.random_int(1000,9999);
   $sale=Sale::create(['invoice_no'=>$invoice,'warehouse_id'=>$warehouse->id,'user_id'=>$request->user()?->id,'customer_name'=>$customerName,'subtotal'=>$subtotal,'discount'=>$discount,'tax'=>$tax,'total'=>$total,'status'=>'completed','sold_at'=>now(),'note'=>$note]);
   foreach($clean as $row){$sale->items()->create(['product_id'=>$row['product']->id,'quantity'=>$row['quantity'],'unit_price'=>$row['unit_price'],'discount'=>0,'line_total'=>$row['line_total']]);$new=(float)$row['stock']->quantity-$row['quantity'];$row['stock']->update(['quantity'=>$new]);StockMovement::create(['product_id'=>$row['product']->id,'warehouse_id'=>$warehouse->id,'user_id'=>$request->user()?->id,'type'=>'sale','quantity'=>-$row['quantity'],'balance_after'=>$new,'reference'=>$invoice,'note'=>'POS sale']);}
   $sale->payments()->create(['method'=>$paymentMethod,'amount'=>$paid,'reference'=>null]);$this->audit->record($request,'sales.sale_created','success',['sale_id'=>$sale->id,'invoice_no'=>$invoice,'warehouse_id'=>$warehouse->id,'total'=>$total]);return $sale->load(['items.product','payments','warehouse']);
  });
 }
}
