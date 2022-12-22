<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchased_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'rate',
        'total',
        'purchasingId',
        'itemType',
        'itemId',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(purchasing::class, 'purchasingId', 'id');
    }


}
