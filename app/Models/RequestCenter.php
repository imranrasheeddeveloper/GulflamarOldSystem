<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'issue',
        'created_at',
        'updated_at',
    ];
}
