<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('expense_categories', function(Blueprint $t){$t->id();$t->string('name',100)->unique();$t->timestamps();});
  Schema::create('expenses', function(Blueprint $t){$t->id();$t->string('reference',50)->unique();$t->foreignId('expense_category_id')->constrained()->restrictOnDelete();$t->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->string('payee',150)->nullable();$t->decimal('amount',14,2);$t->string('payment_method',30);$t->string('status',30)->default('paid');$t->date('expense_date');$t->text('description')->nullable();$t->timestamps();$t->index(['expense_date','expense_category_id']);});
 }
 public function down(): void {Schema::dropIfExists('expenses');Schema::dropIfExists('expense_categories');}
};
