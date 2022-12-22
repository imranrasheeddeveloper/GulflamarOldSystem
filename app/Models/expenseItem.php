<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
    ];

    public function invoiceExpences()
    {
        return $this->hasMany(invoice_expense::class, 'expense_id', 'id');
    }
}
