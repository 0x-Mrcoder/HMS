<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['department' => 'CONS', 'name' => 'General Consultation', 'code' => 'CONS-GEN', 'service_type' => 'consultation', 'base_price' => 6500],
            ['department' => 'CONS', 'name' => 'Specialist Review', 'code' => 'CONS-SPL', 'service_type' => 'consultation', 'base_price' => 12500],
            ['department' => 'LAB', 'name' => 'Full Blood Count', 'code' => 'LAB-FBC', 'service_type' => 'laboratory', 'base_price' => 4500],
            ['department' => 'LAB', 'name' => 'Malaria Parasite Test', 'code' => 'LAB-MPS', 'service_type' => 'laboratory', 'base_price' => 3500],
            ['department' => 'PHRM', 'name' => 'Drug Dispensing Fee', 'code' => 'PHRM-DSP', 'service_type' => 'pharmacy', 'base_price' => 1000],
            ['department' => 'WARD', 'name' => 'Daily Bed Space', 'code' => 'WARD-BED', 'service_type' => 'nursing', 'base_price' => 15000],
            ['department' => 'THTR', 'name' => 'Minor Surgery', 'code' => 'THTR-MIN', 'service_type' => 'theatre', 'base_price' => 85000],
            ['department' => 'NHIS', 'name' => 'NHIS Consultation', 'code' => 'NHIS-CONS', 'service_type' => 'nhis', 'base_price' => 0, 'is_billable' => false],
        ];

        foreach ($services as $service) {
            $department = Department::where('code', $service['department'])->first();
            if (!$department) {
                continue;
            }
            Service::updateOrCreate(
                ['code' => $service['code']],
                [
                    'department_id' => $department->id,
                    'name' => $service['name'],
                    'service_type' => $service['service_type'],
                    'base_price' => $service['base_price'],
                    'is_billable' => $service['is_billable'] ?? true,
                ]
            );
        }
    }
}
