<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pattyCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'transaction_type',
        'assign_to_category',
        'client_location',
        'assign_to_entity',
        'assign_to_entity_id',
        'date',
        'approve',
        'acccept',

        'unit_price',
        'quantity',
        'total',
        'description',
        'attachment',
        'notes',
        'created_by',
        'updated_by',
        'credit_debit',
        'approved_by'
        


    ];

    public function wallets()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

}
