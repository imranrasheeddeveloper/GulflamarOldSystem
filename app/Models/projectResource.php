<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class projectResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function resource()
    {
        return $this->belongsToMany(
        resource_allocation::class,
        'allocated_resources_project_items',
        'projectId',
        'allocatedResourceId',)->withPivot('quantity');
    }

}
