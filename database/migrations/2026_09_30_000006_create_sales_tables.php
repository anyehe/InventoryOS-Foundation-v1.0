<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('sales', function(Blueprint $t){$t->id();$t->string('invoice_no',40)->unique();$t->foreignId('warehouse_id')->constrained()->restrictOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->string('customer_name',150)->nullable();$t->decimal('subtotal',14,2);$t->decimal('discount',14,2)->default(0);$t->decimal('tax',14,2)->default(0);$t->decimal('total',14,2);$t->string('status',30)->default('completed');$t->timestamp('sold_at')->useCurrent();$t->text('note')->nullable();$t->timestamps();$t->index(['warehouse_id','sold_at']);});
  Schema::create('sale_items', function(Blueprint $t){$t->id();$t->foreignId('sale_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->constrained()->restrictOnDelete();$t->decimal('quantity',14,3);$t->decimal('unit_price',14,2);$t->decimal('discount',14,2)->default(0);$t->decimal('line_total',14,2);$t->timestamps();$t->index(['sale_id','product_id']);});
  Schema::create('payments', function(Blueprint $t){$t->id();$t->foreignId('sale_id')->constrained()->cascadeOnDelete();$t->string('method',30);$t->decimal('amount',14,2);$t->string('reference',100)->nullable();$t->timestamps();$t->index(['sale_id','method']);});
 }
 public function down(): void {Schema::dropIfExists('payments');Schema::dropIfExists('sale_items');Schema::dropIfExists('sales');}
};
