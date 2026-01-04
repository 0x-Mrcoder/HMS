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
        \DB::statement("ALTER TABLE visits MODIFY COLUMN visit_type ENUM('opd', 'ipd', 'emergency', 'consultation', 'checkup', 'follow_up') DEFAULT 'consultation'");
        \DB::statement("ALTER TABLE visits MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'billed', 'pending_doctor') DEFAULT 'pending_doctor'");
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
