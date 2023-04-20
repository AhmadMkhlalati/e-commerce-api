<?php

namespace App\Models\RolesAndPermissions;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\RolesAndPermissions\PermissionsServices;
use Spatie\Permission\Models\Permission;

class CustomPermission extends Permission
{
    use HasFactory;
    protected $guard_name = 'web';

    public function allChildren($flatten = false){
        //this function will get all of the children and their nested children also
        return PermissionsServices::getPermissionChildren($this->id, $flatten);
    }

    public function children(){
        return $this->hasMany(self::class ,'parent_id' , 'id' );
    }

    public function parent(){
        return $this->belongsTo(self::class,'parent_id','id');
    }

}
