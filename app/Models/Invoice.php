<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id',
        'invoice_number',
        'status',
        'total_amount',
        'generated_by',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public static function generateNumber()
    {
        return 'INV-' . strtoupper(uniqid());
    }
}
