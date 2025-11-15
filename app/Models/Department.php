<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'head_of_department',
        'description',
        'color',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
