<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendorService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'period',
        'rate',
        'vendor',
    ];

    public function vendor()
    {
        return $this->belongsTo(vendor_base::class, 'vendor', 'id');
    }
}
