<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'procedure_name',
        'surgeon_name',
        'status',
        'materials_used',
        'estimated_cost',
        'actual_cost',
        'scheduled_at',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'materials_used' => 'array',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
