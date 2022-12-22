<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resource_allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'resourceOwnerType',
        'resourceOwnerId',
        'allocationDate',
        'attachment',
        'resource_owner_location',
        'signature',
        'notes',
        'created_by',
        'updated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(employee::class, 'resourceOwnerId', 'emp_id');
    }

    public function project()
    {
        return $this->belongsTo(client_base::class, 'resourceOwnerId', 'client_code');
    }


    public function accommodation()
    {
        return $this->belongsTo(accommodation_base::class, 'resourceOwnerId');
    }

    public function projectItems()
    {
        return $this->belongsToMany(
        projectResource::class,
        'allocated_resources_project_items',
        'allocatedResourceId',
        'projectId')->withPivot('quantity');
    }

    public function employeeItems()
    {
        return $this->belongsToMany(
        employeeResource::class,
        'allocated_resources_employee_items',
        'allocatedResourceId',
        'empId')->withPivot('quantity');
    }

    public function accommodationItems()
    {
        return $this->belongsToMany(
        accommodationResource::class,
        'allocated_resources_accommodation_items',
        'allocatedResourceId',
        'accommodationId')->withPivot('quantity');
    }
}
