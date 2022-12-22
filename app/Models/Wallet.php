<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'account_name',
        'balance',
        'date',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function pattyCash()
    {
        // return $this->hasMany(pattyCash::class, 'id', 'wallet_id')->orderBy('id','desc');
        return $this->hasMany(pattyCash::class)->orderBy('id','desc');

    }
}
