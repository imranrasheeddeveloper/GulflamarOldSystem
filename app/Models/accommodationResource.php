<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accommodationResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    
    public function resource()
    {
        return $this->belongsToMany(
        resource_allocation::class,
        'allocated_resources_accommodation_items',
        'accommodationId',
        'allocatedResourceId',)->withPivot('quantity');
    }
}
