<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'ownerId',
        'assignTo',
        'dueDate',
        'tag',

    ];
    public function Owner()
    {
        return $this->belongsTo(User::class, 'ownerId', 'id');

    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignTo', 'id');

    }
}
