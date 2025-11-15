<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['first_name' => 'Chinedu', 'last_name' => 'Okafor', 'gender' => 'male', 'phone' => '08021234567', 'email' => 'chinedu.okafor@example.com', 'city' => 'Lekki', 'state' => 'Lagos', 'address' => '8 Admiralty Way Lekki', 'nhis_number' => 'NHIS-LAG-001', 'wallet_minimum_balance' => 5000],
            ['first_name' => 'Amina', 'last_name' => 'Abubakar', 'gender' => 'female', 'phone' => '08034567891', 'email' => 'amina.abubakar@example.com', 'city' => 'Garki', 'state' => 'Abuja (FCT)', 'address' => '14 Ahmadu Bello Way', 'wallet_minimum_balance' => 3000],
            ['first_name' => 'Ibrahim', 'last_name' => 'Mohammed', 'gender' => 'male', 'phone' => '07051239876', 'email' => 'ibrahim.mohammed@example.com', 'city' => 'Kano', 'state' => 'Kano', 'address' => '32 Bompai Road', 'wallet_minimum_balance' => 4000],
            ['first_name' => 'Ada', 'last_name' => 'Eze', 'gender' => 'female', 'phone' => '08124567890', 'email' => 'ada.eze@example.com', 'city' => 'Enugu', 'state' => 'Enugu', 'address' => '1 Coal City Garden Estate', 'wallet_minimum_balance' => 3500],
            ['first_name' => 'Timi', 'last_name' => 'Briggs', 'gender' => 'male', 'phone' => '07011223344', 'email' => 'timi.briggs@example.com', 'city' => 'Port Harcourt', 'state' => 'Rivers', 'address' => '7 Aba Road', 'wallet_minimum_balance' => 4500],
        ];

        foreach ($patients as $data) {
            $patient = Patient::create($data);
            $patient->wallet()->create([
                'balance' => rand(20000, 80000) / 1,
                'low_balance_threshold' => $data['wallet_minimum_balance'],
            ]);
        }
    }
}
