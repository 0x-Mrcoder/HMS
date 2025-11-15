<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'drug_name',
        'dosage',
        'frequency',
        'duration',
        'status',
        'unit_price',
        'quantity',
        'total_cost',
        'dispensed_at',
        'dispensed_by',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'dispensed_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
