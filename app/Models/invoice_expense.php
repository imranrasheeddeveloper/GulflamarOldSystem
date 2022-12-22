<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_expense extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_exp_id';

    protected $table = 'invoice_expense';

    public $timestamps = false;

    protected $fillable = [
        'invoice_exp_id',
        'invoice_number',
        'expense_id',
        'quantity',
        'amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(invoice_base::class, 'invoice_number', 'invoice_number');
    }

    public function expenseitem()
    {
        return $this->belongsTo(expenseItem::class, 'expense_id', 'id');
    }
}
