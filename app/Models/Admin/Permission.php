<?php

namespace App\Models\Admin;

use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Traits\HasActiveScope;

class Permission extends SpatiePermission
{
    use HasActiveScope;
}
