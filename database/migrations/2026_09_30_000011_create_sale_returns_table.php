<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('sale_returns', function(Blueprint $t){$t->id();$t->string('reference',50)->unique();$t->foreignId('sale_id')->constrained()->restrictOnDelete();$t->foreignId('warehouse_id')->constrained()->restrictOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->string('status',30)->default('completed');$t->string('refund_method',30);$t->decimal('refund_amount',14,2);$t->string('refund_reference',100)->nullable();$t->string('disposition',30)->default('restock');$t->text('reason')->nullable();$t->timestamp('returned_at')->useCurrent();$t->timestamps();$t->index(['sale_id','returned_at']);});
  Schema::create('sale_return_items', function(Blueprint $t){$t->id();$t->foreignId('sale_return_id')->constrained()->cascadeOnDelete();$t->foreignId('sale_item_id')->constrained()->restrictOnDelete();$t->foreignId('product_id')->constrained()->restrictOnDelete();$t->decimal('quantity',14,3);$t->decimal('unit_price',14,2);$t->decimal('line_total',14,2);$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('sale_return_items');Schema::dropIfExists('sale_returns');}
};
