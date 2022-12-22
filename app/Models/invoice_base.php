<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class invoice_base extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_number';

    protected $table = 'invoice_base';

    public $timestamps = false;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'status',
        'client_code',
        'location_en',
        'month',
        'year',
        'invoice_copy',

        'subtotal',
        'vat',
        'net_total',
        'created_by',
        'updated_by',
    ];

    public function getPeriodAttribute()
    {
        if ($this->year != null && $this->year != '' && $this->month != null && $this->month != '') {
            
            $date = $this->year.'-'.$this->month.'-01';
            $period = Carbon::createFromFormat('Y-m-d', $date);
        }
        else
        {
            $period = '';
        }
        
        return $period;
    }

    public function invoiceExpences()
    {
        return $this->hasMany(invoice_expense::class, 'invoice_number', 'invoice_number')->orderBy('invoice_number','desc');
    }

    public function client()
    {
        return $this->belongsTo(client_base::class, 'client_code', 'client_code');
    }

    /*public function expenses()
    {
        return $this->belongsToMany(
        expenseItem::class,
        invoice_expense::class,
        'invoice_number',
        'expense_id',);
    }
*/

}
