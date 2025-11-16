<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'visit_id',
        'policy_number',
        'provider',
        'claim_status',
        'total_amount',
        'approved_amount',
        'co_pay_amount',
        'co_pay_deducted_at',
        'submitted_at',
        'responded_at',
        'documents',
        'remarks',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'co_pay_amount' => 'decimal:2',
        'documents' => 'array',
        'submitted_at' => 'datetime',
        'responded_at' => 'datetime',
        'co_pay_deducted_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
