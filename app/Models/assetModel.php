<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assetModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'created_by',
        'updated_by',  
    ];
}
