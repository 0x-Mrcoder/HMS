<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'test_name',
        'status',
        'result_summary',
        'result_data',
        'result_at',
    ];

    protected $casts = [
        'result_data' => 'array',
        'result_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
