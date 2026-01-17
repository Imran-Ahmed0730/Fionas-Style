<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasActiveScope;

class Color extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];
}
