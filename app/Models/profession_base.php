<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profession_base extends Model
{
    use HasFactory;

    protected $table = 'profession_base';

    protected $fillable = [
        'profession_en',
        'profession_ar',

    ];

    public function employee()
    {
        return $this->hasMany(employee::class, 'iqama_profession', 'profession_en');
    }
}
