<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchasing extends Model
{
    use HasFactory;

    protected $fillable = [
        /*'orderNo',*/
        'purchaseOrderFile',
        'purchaseType',
        'grandTotal',
        'date',
        'subtotal',
        'vat',
        'net_total',
        'vat_not_vat',
        'seller_type',
        'request_seller',
        'request_seller_id',
        'created_by',
        'updated_by',



    ];

    public function purchasedItems()
    {
        return $this->hasMany(purchased_item::class, 'purchasingId', 'id')->orderBy('id','desc');
    }
}
