<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define specialized doctors to create
        $specialists = [
            ['name' => 'Dr. Ahmed Musa', 'role' => 'Cardiologist', 'dept_code' => 'CARD'],
            ['name' => 'Dr. Sarah Okon', 'role' => 'Gynecologist', 'dept_code' => 'GYN'],
            ['name' => 'Dr. Emeka Obi', 'role' => 'Optometrist', 'dept_code' => 'OPT'],
            ['name' => 'Dr. Fatima Ali', 'role' => 'Pediatrician', 'dept_code' => 'PED'],
            ['name' => 'Dr. John Doe', 'role' => 'General Physician', 'dept_code' => 'GOPD'],
            ['name' => 'Dr. Chioma Eze', 'role' => 'Dermatologist', 'dept_code' => 'DERM'],
            ['name' => 'Dr. Ibrahim Sani', 'role' => 'Neurologist', 'dept_code' => 'NEURO'],
            ['name' => 'Dr. Ngozi Bello', 'role' => 'Orthopedic Surgeon', 'dept_code' => 'ORTHO'],
            ['name' => 'Dr. Kemi Adebayo', 'role' => 'Psychiatrist', 'dept_code' => 'PSYCH'],
            ['name' => 'Dr. Yusuf Garba', 'role' => 'General Physician', 'dept_code' => 'GOPD'],
        ];

        foreach ($specialists as $doc) {
            // Find or Create Department
            // Assuming departments exist or we fallback to 1
            //$dept = \App\Models\Department::where('code', $doc['dept_code'])->first();
            // Since we don't know exact codes in DB, let's just create/find by name approximate or random
            $dept = \App\Models\Department::firstOrCreate(
                ['name' => $doc['dept_code']], // Placeholder name if not found
                ['code' => $doc['dept_code'], 'description' => $doc['role'] . ' Department']
            );

            // Create Authenticated User
            $email = strtolower(str_replace([' ', '.'], '', $doc['name'])) . '@hms.com';
            $user = \App\Models\User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $doc['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => 'doctor',
                    'department_id' => $dept->id,
                ]
            );

            // Create Doctor Profile
            \App\Models\Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'department_id' => $dept->id,
                    'specialization' => $doc['role'],
                    'license_number' => 'MDCN-' . fake()->numerify('#####'),
                    'phone_number' => fake()->phoneNumber(),
                    'bio' => fake()->text(200),
                    'consultation_fee' => 0, // Free as requested
                    'is_available' => true,
                ]
            );
        }
    }
}
