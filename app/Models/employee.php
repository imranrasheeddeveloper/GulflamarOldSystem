<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_id';
    
    protected $table = 'employee_base';

    public $timestamps = false;

    protected $fillable = [
        'emp_id',
        'name',
        'nationality',
        'religion',
        'dob',
        'joining_date',
        'age',
        'contact_number',

        'emp_photo',
        'benefits',
        'iban',
        'vacation_date',
        'notes',
        'iqama_no',
        'iqama_expiry_date',



        'iqama_profession',
        'iqama',
        'passport_number',
        'passport_expiry_date',
        'passport',
        'passport_2',
        'ajeer',

        'ajeer_expiration_date',
        'insurance_card',
        'insurance_card_expirationDate',
        'vendor',   //relation
        'salary_rate',
        'client',  //relation
        'client_location',
        'status',

        'accommodation',
        'contract_start',
        'project_stop_date',
        'lang_eng',
        'lang_ar',
        'lang_hind',
        'appearance_presentable',

        'apprearance_beard',
        'skills',
        'misconduct',
        'rating',
        'created_by',
        'updated_by',

    ];

    public function setAttribute($key, $value)
    {
       parent::setAttribute($key, $value);

        if (is_string($value))
        {
            $this->attributes[$key] = empty(trim($value)) ? null : $value;
        }
    }

    public function resourceAllocations()
    {
        return $this->hasMany(resource_allocation::class, 'resourceOwnerId', 'emp_id')->orderBy('emp_id','desc');
    }


    public function clients()
    {
        return $this->belongsTo(client_base::class, 'client', 'client_code');
    }


    public function vendors()
    {
        return $this->belongsTo(vendor_base::class, 'vendor');
    }

    public function accommodations()
    {
        return $this->belongsTo(accommodation_base::class, 'accommodation');
    }

    public function profession_bases()
    {
        return $this->belongsTo(profession_base::class, 'iqama_profession', 'profession_en');
    }
}
