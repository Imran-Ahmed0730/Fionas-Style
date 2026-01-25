<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasActiveScope;

class Blog extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class)->withDefault([
            'name' => 'N/A',
        ]);
    }
}
