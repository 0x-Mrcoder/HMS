<?php

use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$doctors = [
    ['name' => 'Dr. Ahmed Musa', 'spec' => 'Cardiologist', 'dept' => 'Cardiology'],
    ['name' => 'Dr. Jessica Jones', 'spec' => 'Neurologist', 'dept' => 'Neurology'],
    ['name' => 'Dr. Strange', 'spec' => 'Surgeon', 'dept' => 'Surgery'],
    ['name' => 'Dr. House', 'spec' => 'Diagnostician', 'dept' => 'Internal Medicine'],
    ['name' => 'Dr. Who', 'spec' => 'General Physician', 'dept' => 'GOPD'],
];

foreach ($doctors as $d) {
    echo "Processing {$d['name']}...\n";
    
    // 1. Department
    $dept = Department::firstOrCreate(
        ['name' => $d['dept']],
        ['code' => strtoupper(substr($d['dept'], 0, 3)), 'description' => $d['dept']]
    );

    // 2. User
    $email = strtolower(str_replace([' ', '.'], '', $d['name'])) . '@hms.com';
    $user = User::updateOrCreate(
        ['email' => $email],
        [
            'name' => $d['name'],
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'department_id' => $dept->id,
        ]
    );

    // 3. Doctor Profile
    Doctor::updateOrCreate(
        ['user_id' => $user->id],
        [
            'department_id' => $dept->id,
            'specialization' => $d['spec'],
            'license_number' => 'MDCN-' . rand(10000, 99999),
            'phone_number' => '080' . rand(10000000, 99999999),
            'bio' => "Specialist in {$d['spec']}",
            'consultation_fee' => 0,
            'is_available' => true,
        ]
    );
}

echo "Done seeding doctors.\n";
