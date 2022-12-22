<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userLevelPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'userLevelId',
        'accommodation',
        'addInvoice',
        'allInvoice',
        'employee',
    ];

    public function userLevel()
    {
        return $this->belongsTo(userLevel::class);
    }
}
