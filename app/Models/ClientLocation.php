<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_code',
        'location_name',
        'latitude',
        'longitude',
        'location_map'
    ];

    public function clientLocation()
    {
        return $this->belongsTo(client_base::class, 'client_code', 'client_code');
    }
    public function asset_work_order()
    {
        return $this->belongsTo(asset_work_order::class, 'id', 'location_id');
    }

}
