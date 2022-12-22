<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ownerBank extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_name',
        'bank_name',
        'iban',
        'created_by',
        'updated_by',
    ];
}
