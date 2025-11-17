<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('esa_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('wizard_step')->default(1);
            $table->string('certificate_name')->nullable();
            $table->json('problem_checkboxes')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('terms_accepted_at')->nullable();
            $table->enum('status', ['draft', 'pending', 'reviewing', 'approved', 'rejected'])->default('draft');
            $table->unsignedInteger('fee_cents')->default(9900);
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('esa_requests');
    }
};