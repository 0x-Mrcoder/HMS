<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HospitalSettingSeeder::class,
            DepartmentSeeder::class,
            ServiceSeeder::class,
            PatientSeeder::class,
            HmsDemoSeeder::class,
        ]);

        foreach (config('hms.portals', []) as $slug => $portal) {
            $attributes = [
                'name' => $portal['label'],
                'password' => Hash::make('123456'),
                'role' => $slug,
            ];

            if ($slug === 'patient') {
                $patientRecord = Patient::where('email', 'patient@hms.com')->first();
                if ($patientRecord) {
                    $attributes['patient_id'] = $patientRecord->id;
                }
            }

            User::updateOrCreate(
                ['email' => "{$slug}@hms.com"],
                $attributes
            );
        }
    }
}
