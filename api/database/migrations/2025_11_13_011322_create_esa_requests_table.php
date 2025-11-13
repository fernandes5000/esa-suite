<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('esa_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $table->text('reason');
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->unsignedInteger('fee_cents')->default(9900);
            $table->timestamps();

            $table->index(['status','created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('esa_requests');
    }
};
