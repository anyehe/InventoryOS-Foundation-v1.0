<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('suppliers', function(Blueprint $t){$t->id();$t->string('name');$t->string('code')->unique();$t->string('email')->nullable();$t->string('phone')->nullable();$t->text('address')->nullable();$t->string('tax_id')->nullable();$t->boolean('is_active')->default(true);$t->timestamps();$t->index(['is_active','name']);}); }
 public function down(): void { Schema::dropIfExists('suppliers'); }
};
