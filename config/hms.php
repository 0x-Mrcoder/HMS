<?php

return [
    'portals' => [
        'admin' => [
            'label' => 'Hospital Administrator',
            'tagline' => 'Owner / Super Admin access for the full HMS.',
            'icon' => 'iconoir-shield',
            'accent' => 'primary',
        ],
        'doctor' => [
            'label' => 'Consulting Doctor',
            'tagline' => 'Access patient visits, prescriptions and ward rounds.',
            'icon' => 'iconoir-stethoscope',
            'accent' => 'success',
        ],
        'nurse' => [
            'label' => 'Nursing / Ward',
            'tagline' => 'Manage inpatient activities, vitals and ward allocations.',
            'icon' => 'iconoir-heartbeat',
            'accent' => 'danger',
        ],
        'pharmacy' => [
            'label' => 'Pharmacy Desk',
            'tagline' => 'Dispense medications and monitor wallet deductions.',
            'icon' => 'iconoir-pill',
            'accent' => 'info',
        ],
        'laboratory' => [
            'label' => 'Laboratory Scientist',
            'tagline' => 'Receive requests, capture results and bill wallets.',
            'icon' => 'iconoir-lab',
            'accent' => 'warning',
        ],
        'theatre' => [
            'label' => 'Theatre Coordinator',
            'tagline' => 'Schedule procedures and track surgical notes.',
            'icon' => 'iconoir-scissors',
            'accent' => 'secondary',
        ],
        'insurance' => [
            'label' => 'NHIS / Insurance',
            'tagline' => 'Submit claims and process co-pay balances.',
            'icon' => 'iconoir-hospital',
            'accent' => 'primary',
        ],
        'accountant' => [
            'label' => 'Accounts & Billing',
            'tagline' => 'Manage cash office postings and financial reports.',
            'icon' => 'iconoir-receipt',
            'accent' => 'dark',
        ],
        'patient' => [
            'label' => 'Patient Self-Service',
            'tagline' => 'Wallet funding, appointment tracking and statements.',
            'icon' => 'iconoir-user-badge',
            'accent' => 'success',
        ],
    ],
];
