<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assetsManagment extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_name',
        'year', 
        'fuel_type',
        'chassis_number', 
        'legal_documents' ,
        'mileage', 
        'notes',
        'created_by',
        'updated_by',
        'asset_type_id',
        'asset_model_id',
        'asset_make_id',
        'asset_capacity_id',
    ];

     public function assetType(){
    return $this->hasOne(assetType::class, 'id', 'asset_type_id');
    }
    public function assetModel(){
    return $this->hasOne(assetModel::class, 'id', 'asset_model_id');
    }
    public function assetMake(){
    return $this->hasOne(assetMake::class, 'id', 'asset_make_id');
    }
    public function assetCapacity(){
    return $this->hasOne(assetCapacity::class, 'id', 'asset_capacity_id');
    }
}
