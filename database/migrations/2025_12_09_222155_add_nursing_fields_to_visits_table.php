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
        Schema::table('visits', function (Blueprint $table) {
            if (!Schema::hasColumn('visits', 'ward_id')) $table->foreignId('ward_id')->nullable()->constrained();
            if (!Schema::hasColumn('visits', 'bed_id')) $table->foreignId('bed_id')->nullable()->constrained();
            if (!Schema::hasColumn('visits', 'doctor_id')) $table->foreignId('doctor_id')->nullable()->constrained('users');
            if (!Schema::hasColumn('visits', 'symptoms')) $table->text('symptoms')->nullable();
            if (!Schema::hasColumn('visits', 'diagnosis')) $table->text('diagnosis')->nullable();
            if (!Schema::hasColumn('visits', 'admission_date')) $table->dateTime('admission_date')->nullable();
            if (!Schema::hasColumn('visits', 'discharge_date')) $table->dateTime('discharge_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            //
        });
    }
};
