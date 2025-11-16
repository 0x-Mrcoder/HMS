<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'card_number',
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'lga',
        'blood_group',
        'genotype',
        'allergies',
        'nhis_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'wallet_minimum_balance',
        'photo_url',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function (Patient $patient) {
            if (!$patient->hospital_id) {
                $patient->hospital_id = 'HMS-' . strtoupper(Str::random(6));
            }
            if (!$patient->card_number) {
                $patient->card_number = 'CARD-' . strtoupper(Str::random(6));
            }
        });
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
