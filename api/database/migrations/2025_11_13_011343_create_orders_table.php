<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esa_request_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount_cents');
            $table->string('provider')->default('braintree');
            $table->string('status')->default('created');
            $table->string('external_id')->nullable();
            $table->string('payment_method_last4')->nullable();
            $table->json('webhook_payload')->nullable();
            $table->timestamps();

            $table->index(['status','created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
