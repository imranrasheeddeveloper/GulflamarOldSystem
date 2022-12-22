<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accommodationPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_base_id',
        'amount',
        'date',
        'notes',
        'periodFrom',
        'periodTo',
        'attachment',
        'rent_reciept',
    ];

    public function accommodations()
    {
        return $this->belongsTo(accommodation_base::class, 'accommodation_base_id');
    }
}
