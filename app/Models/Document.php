<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_type',
        'recipient',
        'attachment',
        'date',
        'created_by',
        'updated_by',
        'description',
        'resource_owner_id',
        'resource_owner_name',
        'method',
        'received_by',
        'received_at',



    ];
}
