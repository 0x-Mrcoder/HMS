<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Consulting', 'code' => 'CONS', 'category' => 'Clinical', 'description' => 'Doctors clinic and consultation rooms', 'color' => '#4b9bfa'],
            ['name' => 'Pharmacy', 'code' => 'PHRM', 'category' => 'Support', 'description' => 'Dispensing, stock control and billing', 'color' => '#3ad29f'],
            ['name' => 'Laboratory', 'code' => 'LAB', 'category' => 'Diagnostics', 'description' => 'Sample collection and diagnostics', 'color' => '#fd8451'],
            ['name' => 'Nursing/Ward', 'code' => 'WARD', 'category' => 'IPD', 'description' => 'Bed management and nursing procedures', 'color' => '#a071f8'],
            ['name' => 'Theatre', 'code' => 'THTR', 'category' => 'Surgical', 'description' => 'Operations and post-op follow-up', 'color' => '#ffe066'],
            ['name' => 'NHIS/Insurance', 'code' => 'NHIS', 'category' => 'Insurance', 'description' => 'Claims processing and co-pay', 'color' => '#ef476f'],
            ['name' => 'Accounts', 'code' => 'ACCT', 'category' => 'Finance', 'description' => 'Hospital billing and cash office', 'color' => '#118ab2'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
