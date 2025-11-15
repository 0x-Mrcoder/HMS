<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'balance',
        'low_balance_threshold',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'low_balance_threshold' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getLowBalanceAttribute(): bool
    {
        return $this->balance <= $this->low_balance_threshold;
    }
}
