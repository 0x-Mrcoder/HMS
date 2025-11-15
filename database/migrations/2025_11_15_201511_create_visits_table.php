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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('visit_type', ['opd', 'ipd', 'emergency'])->default('opd');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'billed'])->default('pending');
            $table->string('doctor_name')->nullable();
            $table->string('reason')->nullable();
            $table->json('vitals')->nullable();
            $table->decimal('estimated_cost', 12, 2)->default(0);
            $table->decimal('amount_charged', 12, 2)->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
