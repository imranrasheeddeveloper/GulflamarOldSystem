<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplierId',
        'typeId',

    ];
    public function supplierTypes()
    {
        return $this->hasOne(supplierType::class, 'id', 'typeId');

    }
}
