<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function permissions()
    {
        return $this->hasOne(userLevelPermission::class, 'userLevelId');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'role')->orderBy('id','desc');
    }
}
