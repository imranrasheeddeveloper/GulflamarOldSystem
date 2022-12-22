<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_payment_id',
        'request_type',
        'request_owner',
        'request_owner_id',
        'payment_type',
        'amount',
        'status',
        'attachment',
        'notes',
        'created_by',
        'updated_by',


    ];
}
