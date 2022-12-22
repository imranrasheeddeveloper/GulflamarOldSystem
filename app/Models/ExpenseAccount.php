<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_name',
        'month',
        'year',
        'created_by',
        'updated_by',
    ];
}
