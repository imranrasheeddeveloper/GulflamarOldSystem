<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accommodation_base extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    
    protected $table = 'accommodation_base';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'location',
        'address',
        'rooms',
        'beds',
        'contract_startdate',
        'contract_enddate',
        'latitude',
        'longitude',
        'rent',
        'services',
        'due-date',
        'period',
        'apartment',
        'electricityAccountNo',
        'waterBillAccountNo',
        'contactName',
        'contactNo',
        'accountName',
        'bankName',
        'iban',
        'fixedElectricityAmount',
        'fixedWaterAmount',
        'notes',
        'created_by',
        'updated_by',


    ];

    public function employee()
    {
        return $this->hasMany(employee::class, 'accommodation')->orderBy('id','desc');
    }

    public function resourceAllocations()
    {
        return $this->hasMany(resource_allocation::class, 'resourceOwnerId')->orderBy('id','desc');
    }

    public function payments()
    {
        return $this->hasMany(accommodationPayment::class, 'accommodation_base_id')->orderBy('id','desc');
    }

    public function billPayments()
    {
        return $this->hasMany(accommodationBillPayment::class, 'accommodation_base_id')->orderBy('id','desc');
    }

}
