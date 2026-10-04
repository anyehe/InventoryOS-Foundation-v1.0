<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('api_idempotency_keys', function(Blueprint $t){ $t->uuid('id')->primary(); $t->foreignId('api_key_id')->nullable()->constrained('api_keys')->nullOnDelete(); $t->string('idempotency_key',100); $t->string('request_hash',64); $t->unsignedSmallInteger('status_code')->nullable(); $t->longText('response_body')->nullable(); $t->timestamps(); $t->unique(['api_key_id','idempotency_key']); }); }
 public function down(): void { Schema::dropIfExists('api_idempotency_keys'); }
};
