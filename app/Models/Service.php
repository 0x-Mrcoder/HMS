<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name',
        'code',
        'service_type',
        'base_price',
        'is_billable',
        'description',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
