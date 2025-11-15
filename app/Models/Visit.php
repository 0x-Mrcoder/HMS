<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'department_id',
        'service_id',
        'visit_type',
        'status',
        'doctor_name',
        'reason',
        'vitals',
        'estimated_cost',
        'amount_charged',
        'scheduled_at',
        'completed_at',
    ];

    protected $casts = [
        'vitals' => 'array',
        'estimated_cost' => 'decimal:2',
        'amount_charged' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function nursingNotes()
    {
        return $this->hasMany(NursingNote::class);
    }
}
