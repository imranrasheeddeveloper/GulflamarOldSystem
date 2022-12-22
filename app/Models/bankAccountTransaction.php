<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bankAccountTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_account_id',
        'transaction_type',
        'date',
        'sub_type',
        'amount',
        'to_from',
        'notes',
        'created_by',
        'updated_by',
        'credit_debit',
    ];
}
