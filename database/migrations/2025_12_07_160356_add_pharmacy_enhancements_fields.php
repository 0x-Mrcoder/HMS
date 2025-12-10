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
        if (!Schema::hasColumn('drugs', 'expiry_date')) {
            Schema::table('drugs', function (Blueprint $table) {
                $table->date('expiry_date')->nullable()->after('stock');
            });
        }

        if (!Schema::hasColumn('prescriptions', 'rejection_reason')) {
            Schema::table('prescriptions', function (Blueprint $table) {
                $table->text('rejection_reason')->nullable()->after('status');
            });
        }

        if (!Schema::hasTable('drug_stock_logs')) {
            Schema::create('drug_stock_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('drug_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained(); // Who made the change
                $table->integer('quantity_change'); // +ve for in, -ve for out
                $table->string('type'); // in, out, adjustment, dispensed
                $table->string('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_stock_logs');

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });

        Schema::table('drugs', function (Blueprint $table) {
            $table->dropColumn('expiry_date');
        });
    }
};
