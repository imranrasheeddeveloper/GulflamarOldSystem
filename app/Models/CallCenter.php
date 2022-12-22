<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallCenter extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'name_id',
        'project', 
        'date',
        'time',
        'department',
        'status',
        'resolution', 
        'issue' ,
        'issue_resolution', 
        'method_of_contact',
        'contact_detail',
        'notes', 
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'department', 'id');
    }
}
