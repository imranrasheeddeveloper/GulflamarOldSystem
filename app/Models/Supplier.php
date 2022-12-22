<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'name_ar',
        'vat_number',
        'cr_number',
        'contact_name',
        'contact_number',
        'city',
        'bank_name',
        'account_name',
        'iban',
        'created_by',
        'updated_by',

    ];

    public function supplierItems()
    {
        return $this->hasMany(SupplierItem::class, 'supplierId', 'id')->with('supplierTypes');

    }
}
