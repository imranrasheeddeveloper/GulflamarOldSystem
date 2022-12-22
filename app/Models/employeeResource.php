<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeeResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function resource()
    {
        return $this->belongsToMany(
        resource_allocation::class,
        'allocated_resources_employee_items',
        'empId',
        'allocatedResourceId',)->withPivot('quantity');
    }
}
