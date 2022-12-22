<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'initiated_by',
        'total_amount',
        'status',
        'date',
        'created_by',
        'updated_by',


    ];

    public function Payments()
    {
        return $this->hasMany(Payment::class, 'request_payment_id', 'id');

    }
}
