<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assetMaintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_id',
        'asset_name',
        'maintenance_date',
        'maintenance_type',
        'last_reading',
        'attachment',
        'notes',
        'created_by',
        'updated_by',
        
    ];
    public function asset(){
    return $this->hasMany(assetsManagment::class, 'id', 'asset_id');
    }
}
