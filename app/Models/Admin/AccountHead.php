<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
