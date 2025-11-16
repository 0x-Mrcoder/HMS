<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('policy_number');
            $table->string('provider');
            $table->enum('claim_status', ['draft', 'submitted', 'approved', 'rejected', 'paid'])->default('draft');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('approved_amount', 12, 2)->default(0);
            $table->decimal('co_pay_amount', 12, 2)->default(0);
            $table->timestamp('co_pay_deducted_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->json('documents')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_claims');
    }
};
