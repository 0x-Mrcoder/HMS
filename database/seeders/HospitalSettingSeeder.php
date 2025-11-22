<?php

namespace Database\Seeders;

use App\Models\HospitalSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HospitalSettingSeeder extends Seeder
{
    public function run(): void
    {
        HospitalSetting::updateOrCreate(
            ['slug' => 'cyberhausa-clinic'],
            [
                'name' => 'CyberHausa Clinic',
                'tagline' => 'Digital-first patient care.',
                'logo_path' => 'storage/hospital/cyberhausa-logo.svg',
                'address_line1' => '42 Innovation Crescent',
                'city' => 'Abuja',
                'state' => 'FCT',
                'country' => 'Nigeria',
                'phone' => '+234-800-123-4567',
                'email' => 'info@cyberhausa.health',
                'website' => 'https://cyberhausa.health',
                'timezone' => 'Africa/Lagos',
                'currency' => 'NGN',
                'primary_color' => '#ef4444',
                'secondary_color' => '#0f172a',
                'extra' => [
                    'registration_prefix' => 'CHC-' . Str::upper(Str::random(3)),
                    'show_wallet_alerts' => true,
                ],
            ]
        );
    }
}
