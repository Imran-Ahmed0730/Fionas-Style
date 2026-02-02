<?php

namespace App\Models\Admin;

use App\Traits\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, HasActiveScope;
    protected $guarded = ['id'];
}
