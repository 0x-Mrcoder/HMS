<?php

return [
    'defaults' => [
        'name' => env('HOSPITAL_NAME', 'Hospital Management System'),
        'slug' => 'default',
        'tagline' => env('HOSPITAL_TAGLINE', 'Integrated Hospital Suite'),
        'logo_path' => env('HOSPITAL_LOGO', null),
        'address_line1' => env('HOSPITAL_ADDRESS', null),
        'address_line2' => null,
        'city' => env('HOSPITAL_CITY', null),
        'state' => env('HOSPITAL_STATE', null),
        'country' => env('HOSPITAL_COUNTRY', null),
        'phone' => env('HOSPITAL_PHONE', null),
        'email' => env('HOSPITAL_EMAIL', null),
        'website' => env('HOSPITAL_WEBSITE', null),
        'timezone' => env('APP_TIMEZONE', config('app.timezone', 'UTC')),
        'currency' => env('HOSPITAL_CURRENCY', 'NGN'),
        'primary_color' => env('HOSPITAL_PRIMARY_COLOR', '#dc2626'),
        'secondary_color' => env('HOSPITAL_SECONDARY_COLOR', '#0f172a'),
        'extra' => [],
    ],
];
