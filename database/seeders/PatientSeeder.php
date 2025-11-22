<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['first_name' => 'Chinedu', 'last_name' => 'Okafor', 'gender' => 'male', 'phone' => '08021234567', 'email' => 'chinedu.okafor@example.com', 'city' => 'Lekki', 'state' => 'Lagos', 'address' => '8 Admiralty Way Lekki', 'nhis_number' => 'NHIS-LAG-001', 'wallet_minimum_balance' => 5000, 'photo_url' => 'https://images.unsplash.com/photo-1504593811423-6dd665756598?auto=format&fit=crop&w=300&q=80'],
            ['first_name' => 'Amina', 'last_name' => 'Abubakar', 'gender' => 'female', 'phone' => '08034567891', 'email' => 'amina.abubakar@example.com', 'city' => 'Garki', 'state' => 'Abuja (FCT)', 'address' => '14 Ahmadu Bello Way', 'wallet_minimum_balance' => 3000, 'photo_url' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=300&q=80'],
            ['first_name' => 'Ibrahim', 'last_name' => 'Mohammed', 'gender' => 'male', 'phone' => '07051239876', 'email' => 'ibrahim.mohammed@example.com', 'city' => 'Kano', 'state' => 'Kano', 'address' => '32 Bompai Road', 'wallet_minimum_balance' => 4000, 'photo_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=300&q=80'],
            ['first_name' => 'Ada', 'last_name' => 'Eze', 'gender' => 'female', 'phone' => '08124567890', 'email' => 'ada.eze@example.com', 'city' => 'Enugu', 'state' => 'Enugu', 'address' => '1 Coal City Garden Estate', 'wallet_minimum_balance' => 3500, 'photo_url' => 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=300&q=80'],
            ['first_name' => 'Timi', 'last_name' => 'Briggs', 'gender' => 'male', 'phone' => '07011223344', 'email' => 'timi.briggs@example.com', 'city' => 'Port Harcourt', 'state' => 'Rivers', 'address' => '7 Aba Road', 'wallet_minimum_balance' => 4500, 'photo_url' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=300&q=80'],
            ['first_name' => 'Usman', 'last_name' => 'Umar', 'gender' => 'male', 'phone' => '08090011223', 'email' => 'patient@hms.com', 'city' => 'Abuja (FCT)', 'state' => 'Abuja (FCT)', 'address' => 'HMS VIP Ward, Central Business District', 'nhis_number' => 'NHIS-FCT-901', 'wallet_minimum_balance' => 8000, 'photo_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80', 'emergency_contact_name' => 'Maryam Umar', 'emergency_contact_phone' => '08056667777'],
        ];

        foreach ($patients as $index => $data) {
            $patient = Patient::updateOrCreate(
                ['email' => $data['email']],
                [
                    ...$data,
                    'hospital_id' => 'HMS-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'card_number' => 'CARD-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                ]
            );

            $patient->wallet()->updateOrCreate(
                [],
                [
                    'balance' => rand(20000, 80000),
                    'low_balance_threshold' => $data['wallet_minimum_balance'],
                    'virtual_account_number' => $patient->wallet?->virtual_account_number ?? \App\Models\Wallet::generateVirtualAccountNumber(),
                ]
            );
        }
    }
}
