<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accommodationBillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_base_id',
        'amount',
        'date',
        'billType',
        'billMonth', // 2/2021 etc
        'attachment',
        'notes',
    ];

    public function accommodations()
    {
        return $this->belongsTo(accommodation_base::class, 'accommodation_base_id');
    }
}
