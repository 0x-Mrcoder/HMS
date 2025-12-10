<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'staff@hms.com'],
            [
                'name' => 'Front Desk Staff',
                'role' => 'staff',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );
        
        $this->command->info('Staff User created: staff@hms.com / 123456');
    }
}
