<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('categories', function(Blueprint $t){$t->id();$t->string('name')->unique();$t->string('description')->nullable();$t->boolean('is_active')->default(true);$t->timestamps();});
  Schema::create('brands', function(Blueprint $t){$t->id();$t->string('name')->unique();$t->boolean('is_active')->default(true);$t->timestamps();});
  Schema::create('units', function(Blueprint $t){$t->id();$t->string('name')->unique();$t->string('short_name',20)->unique();$t->decimal('conversion_rate',12,4)->default(1);$t->timestamps();});
  Schema::create('warehouses', function(Blueprint $t){$t->id();$t->string('name');$t->string('code',30)->unique();$t->string('location')->nullable();$t->boolean('is_active')->default(true);$t->timestamps();});
  Schema::create('products', function(Blueprint $t){$t->id();$t->string('name');$t->string('sku')->unique();$t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();$t->decimal('cost_price',15,2)->default(0);$t->decimal('selling_price',15,2)->default(0);$t->decimal('reorder_level',15,3)->default(0);$t->boolean('is_active')->default(true);$t->text('description')->nullable();$t->timestamps();});
  Schema::create('warehouse_stocks', function(Blueprint $t){$t->id();$t->foreignId('warehouse_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->decimal('quantity',15,3)->default(0);$t->timestamps();$t->unique(['warehouse_id','product_id']);});
  Schema::create('stock_movements', function(Blueprint $t){$t->id();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->foreignId('warehouse_id')->constrained()->cascadeOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->string('type',30);$t->decimal('quantity',15,3);$t->decimal('balance_after',15,3);$t->string('reference')->nullable();$t->text('note')->nullable();$t->timestamps();$t->index(['product_id','warehouse_id','created_at']);});
  Schema::create('stock_transfers', function(Blueprint $t){$t->id();$t->string('reference')->unique();$t->foreignId('from_warehouse_id')->constrained('warehouses');$t->foreignId('to_warehouse_id')->constrained('warehouses');$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->decimal('quantity',15,3);$t->string('status',20)->default('completed');$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->text('note')->nullable();$t->timestamps();});
 }
 public function down(): void { foreach(['stock_transfers','stock_movements','warehouse_stocks','products','warehouses','units','brands','categories'] as $t) Schema::dropIfExists($t); }
};
