<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('purchases', function(Blueprint $t){$t->id();$t->string('purchase_no')->unique();$t->foreignId('supplier_id')->constrained()->restrictOnDelete();$t->foreignId('warehouse_id')->constrained()->restrictOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->enum('status',['draft','ordered','partial','received','cancelled'])->default('draft');$t->decimal('subtotal',14,2)->default(0);$t->decimal('discount',14,2)->default(0);$t->decimal('tax',14,2)->default(0);$t->decimal('total',14,2)->default(0);$t->date('expected_at')->nullable();$t->timestamp('ordered_at')->nullable();$t->timestamp('received_at')->nullable();$t->text('note')->nullable();$t->timestamps();$t->index(['supplier_id','status']);});
  Schema::create('purchase_items', function(Blueprint $t){$t->id();$t->foreignId('purchase_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->constrained()->restrictOnDelete();$t->decimal('quantity',14,3);$t->decimal('received_quantity',14,3)->default(0);$t->decimal('returned_quantity',14,3)->default(0);$t->decimal('unit_cost',14,2);$t->decimal('line_total',14,2);$t->timestamps();$t->unique(['purchase_id','product_id']);$t->index('product_id');});
  Schema::create('purchase_returns', function(Blueprint $t){$t->id();$t->string('return_no')->unique();$t->foreignId('purchase_id')->constrained()->restrictOnDelete();$t->foreignId('supplier_id')->constrained()->restrictOnDelete();$t->foreignId('warehouse_id')->constrained()->restrictOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->decimal('total',14,2)->default(0);$t->text('reason')->nullable();$t->timestamps();});
  Schema::create('purchase_return_items', function(Blueprint $t){$t->id();$t->foreignId('purchase_return_id')->constrained()->cascadeOnDelete();$t->foreignId('purchase_item_id')->constrained()->restrictOnDelete();$t->decimal('quantity',14,3);$t->decimal('unit_cost',14,2);$t->decimal('line_total',14,2);$t->timestamps();});
 } 
 public function down(): void { Schema::dropIfExists('purchase_return_items');Schema::dropIfExists('purchase_returns');Schema::dropIfExists('purchase_items');Schema::dropIfExists('purchases'); }
};
