<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTemplate extends Model
{
    protected $fillable = ['test_name', 'fields'];

    protected $casts = [
        'fields' => 'array',
    ];
    //
}
