<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->string('virtual_account_number')->nullable()->unique()->after('low_balance_threshold');
        });

        // Backfill existing wallets with unique static numbers.
        $existing = DB::table('wallets')->whereNull('virtual_account_number')->get(['id']);
        foreach ($existing as $wallet) {
            $number = $this->generateUniqueNumber();
            DB::table('wallets')->where('id', $wallet->id)->update([
                'virtual_account_number' => $number,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropUnique(['virtual_account_number']);
            $table->dropColumn('virtual_account_number');
        });
    }

    protected function generateUniqueNumber(): string
    {
        do {
            $candidate = 'VA' . Str::upper(Str::random(10));
            $exists = DB::table('wallets')->where('virtual_account_number', $candidate)->exists();
        } while ($exists);

        return $candidate;
    }
};
