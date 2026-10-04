<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('customers', function(Blueprint $t){$t->id();$t->string('name',150);$t->string('code',40)->unique();$t->string('email',190)->nullable();$t->string('phone',40)->nullable();$t->text('address')->nullable();$t->decimal('credit_limit',14,2)->default(0);$t->boolean('is_active')->default(true);$t->timestamps();$t->index(['name','is_active']);});
  Schema::table('sales', function(Blueprint $t){$t->foreignId('customer_id')->nullable()->after('warehouse_id')->constrained('customers')->nullOnDelete();});
 }
 public function down(): void { Schema::table('sales',fn(Blueprint $t)=>$t->dropForeign(['customer_id'])->dropColumn('customer_id')); Schema::dropIfExists('customers'); }
};
