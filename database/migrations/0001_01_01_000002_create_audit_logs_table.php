<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('audit_logs', function(Blueprint $table){$table->id();$table->foreignId('user_id')->nullable()->index();$table->string('action',120);$table->string('resource_type',120)->nullable();$table->string('resource_id',120)->nullable();$table->string('ip_address',45)->nullable();$table->string('request_id',100)->nullable()->index();$table->string('result',30)->default('success');$table->json('metadata')->nullable();$table->timestamps();$table->index(['action','created_at']);}); } public function down(): void { Schema::dropIfExists('audit_logs'); } };
