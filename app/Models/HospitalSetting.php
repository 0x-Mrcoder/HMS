<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'logo_path',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'phone',
        'email',
        'website',
        'timezone',
        'currency',
        'primary_color',
        'secondary_color',
        'extra',
    ];

    protected $casts = [
        'extra' => 'array',
    ];

    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->country,
        ]);

        return empty($parts) ? null : implode(', ', $parts);
    }
}
