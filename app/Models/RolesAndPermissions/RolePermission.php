<?php

namespace App\Models\RolesAndPermissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;


class RolePermission extends Pivot
{
    use HasFactory;
    protected $table = "role_has_permissions";
    protected $guard_name = 'web';

}
