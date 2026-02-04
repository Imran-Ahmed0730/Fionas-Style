<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'message',
    ];
}
