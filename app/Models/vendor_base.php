<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor_base extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'vendor_base';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'name_ar',
        'cr_no',
        'cr_file',
        'vat_no',
        'vat_file',
        'contract',
        'contact_ops',
        'contact_ops_no',
        'contact_billing',
        'contact_billing_no',
        'contact_gov',
        'contact_gov_no',

    ];

    public function setAttribute($key, $value)
    {
       parent::setAttribute($key, $value);

        if (is_string($value))
        {
            $this->attributes[$key] = empty(trim($value)) ? null : $value;
        }
    }

    public static function boot() {
        parent::boot();

        static::deleted(function($vendor) {
             $vendor->vendorServices()->get()->each->delete();
        });
    }

    public function vendorServices()
    {
        return $this->hasMany(vendorService::class, 'vendor', 'id')->orderBy('id','desc');
    }

    public function employee()
    {
        return $this->hasMany(employee::class, 'vendor')->orderBy('id','desc');
    }
}
