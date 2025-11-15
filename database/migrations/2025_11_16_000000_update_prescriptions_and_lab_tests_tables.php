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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 2)->default(0)->after('status');
            $table->unsignedInteger('quantity')->default(1)->after('unit_price');
            $table->decimal('total_cost', 12, 2)->default(0)->after('quantity');
            $table->timestamp('dispensed_at')->nullable()->after('notes');
            $table->string('dispensed_by')->nullable()->after('dispensed_at');
        });

        Schema::table('lab_tests', function (Blueprint $table) {
            $table->string('technician_name')->nullable()->after('test_name');
            $table->decimal('charge_amount', 12, 2)->default(0)->after('result_summary');
            $table->timestamp('charged_at')->nullable()->after('charge_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'quantity', 'total_cost', 'dispensed_at', 'dispensed_by']);
        });

        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropColumn(['technician_name', 'charge_amount', 'charged_at']);
        });
    }
};
