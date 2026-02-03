<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    protected $guarded = ['id'];

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class);
    }
}
