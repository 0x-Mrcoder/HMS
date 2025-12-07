<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ward;
use App\Models\Bed;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $wards = [
            ['name' => 'Male General Ward', 'type' => 'male', 'capacity' => 10],
            ['name' => 'Female General Ward', 'type' => 'female', 'capacity' => 10],
            ['name' => 'Pediatric Ward', 'type' => 'pediatric', 'capacity' => 8],
            ['name' => 'ICU', 'type' => 'icu', 'capacity' => 4],
            ['name' => 'Maternity Ward', 'type' => 'maternity', 'capacity' => 6],
        ];

        foreach ($wards as $wardData) {
            $ward = Ward::create($wardData);

            // Create Beds for each Ward
            for ($i = 1; $i <= $ward->capacity; $i++) {
                Bed::create([
                    'ward_id' => $ward->id,
                    'number' => strtoupper(substr($ward->type, 0, 1)) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'status' => 'available',
                ]);
            }
        }
    }
}
