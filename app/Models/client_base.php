<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client_base extends Model
{
    use HasFactory;

    protected $table = 'client_base';

    protected $primaryKey = 'client_code';

    public $timestamps = false;

    protected $fillable = [
        'client_name',
        'client_name_ar',
        'client_code',
        'address',
        'cr_no',
        'vat_no',
        'cr',

        'vat_cert',
        'ajeer_license',
        'dept_en',
        'dept_ar',
        'contract',
        'contract_type',

        'contract_status',
        'fat_details',
        /*'services',*/
        'rate1',
        'rate2',
        'overtime_rate1',


        'overtime_rate2',
        'start_date',
        'end_date',
        'days-week',
        'hours-day',
        'contactName',
        'account_name',
        'bank_name',
        'iban',
        'contactNo',
        'created_by',
        'updated_by',

    ];

    public static function boot() {
        parent::boot();

        static::deleted(function($client) {
             $client->clientServices()->get()->each->delete();
        });
    }

    public function setAttribute($key, $value)
    {
       parent::setAttribute($key, $value);

        if (is_string($value))
        {
            $this->attributes[$key] = empty(trim($value)) ? null : $value;
        }
    }

    public function clientServices()
    {
        return $this->hasMany(clientService::class, 'client', 'client_code')->orderBy('id','desc');
    }
    public function clinetLocation(){
        return $this->hasMany(ClientLocation::class, 'client_code', 'client_code')->orderBy('id','desc');
    }

    public function invoices()
    {
        return $this->hasMany(invoice_base::class, 'client_code', 'client_code')->orderBy('invoice_number','desc');
    }

    public function employee()
    {
        return $this->hasMany(employee::class, 'client', 'client_code')->orderBy('client_code','desc');
    }

    public function resourceAllocations()
    {
        return $this->hasMany(resource_allocation::class, 'resourceOwnerId', 'client_code')->orderBy('client_code','desc');
    }
    public function asset_work_order()
    {
        return $this->belongsTo(asset_work_order::class, 'id', 'c_id');
    }
    
}
