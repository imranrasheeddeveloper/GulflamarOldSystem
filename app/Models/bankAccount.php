<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bankAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_name',
        'bank_name',
        'iban',
        'balance',
        'created_by',
        'updated_by',
    ];
}
