<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasActiveScope;

class Slider extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];
}
