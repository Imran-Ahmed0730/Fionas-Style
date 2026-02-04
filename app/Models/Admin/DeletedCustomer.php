<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DeletedCustomer extends Model
{
    protected $fillable = ['name', 'phone', 'email'];
}
