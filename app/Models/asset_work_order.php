<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asset_work_order extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_id',
        'fromDate',
        'toDate',
        'c_id',
        'location_id',
        'rate_basis',
        'rate',
        'check_out_reading',
        'check_in_reading',
        'notes',
        'scope',
        'asset_name',
        'location_name',
        'client_name',
        'created_by',
        'updated_by',
        
        
    ];

    public function client(){
    return $this->hasMany(client_base::class, 'client_code', 'c_id');
    }
    public function location(){
    return $this->hasMany(ClientLocation::class, 'id', 'location_id');
    }
    public function asset(){
    return $this->hasMany(assetsManagment::class, 'id', 'asset_id');
    }
}
