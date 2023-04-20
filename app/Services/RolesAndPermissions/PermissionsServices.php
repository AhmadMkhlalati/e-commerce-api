<?php

namespace App\Services\RolesAndPermissions;

use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use PhpParser\Node\Expr\Cast\Object_;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionsServices {
    public static function getPermissionChildren(int | Permission $permission,$permissionsOfRole = [],$allPermissions = [], $flatten= false) : Array
    {

        $allPermissions = CustomPermission::query()->whereIn('id',collect($allPermissions)->pluck('id')->toArray())->get();

        // we passed the main role that we want to get its children along with the roles and there children
        $permissionChildren = self::generateChildrenForAllPermissions($allPermissions);
        //if the given data was numeric then take it as the roleId if not then take the id of the passed object
        $permissionId = (is_numeric($permission) ? $permission : $permission->id);

        return self::drawPermissionChildren($permissionId, $permissionChildren,!$flatten, $allPermissions,$permissionsOfRole);
    }


    public static function generateChildrenForAllPermissions($allPermissions):Array {
        $permissionChildren = [];
        foreach($allPermissions as $currentPermission){
            // get the parent ID if there is any if not set to 0, so the values of the first index `0` do not have any parents
            $parentId = ($currentPermission->parent_id ?? 0);

            // if the index was not set we just set it.
            if(!isset($permissionChildren[$parentId])){
                $permissionChildren[$parentId] = [];
            }
            // under the given index we add a new value the new child of that index
            $permissionChildren[$parentId][] = CustomPermission::find($currentPermission->id);
        }

        return $permissionChildren;
    }

    /**
     * @param Int $parentPermissionId
     * @param array $allPermissionsID
     * @param $isMultiLevel
     * @param $allPermissions
     * @return Array
     */                                                 //parentCategoryId        //all categories      // true
    public static function drawPermissionChildren(Int $parentPermissionId, Array $allPermissionsID , $isMultiLevel = false, $allPermissions,$permissionsOfRole=[]): Array{ //with levels
        $childpermissions = array();
        $permissionsOfRoleIds= array_column($permissionsOfRole, 'id');
        if(empty($allPermissionsID[$parentPermissionId])){
            return [];
        }
        foreach($allPermissionsID[$parentPermissionId] as $permissionId){

            $permissionId =  is_numeric($permissionId)? ($permissionId) : $permissionId->id;

            if($isMultiLevel){
                $childpermissions[$permissionId] = [
                    'id' => $allPermissions->find($permissionId)->id,
                    'label' => $allPermissions->find($permissionId)->name,
                    'checked' => in_array($allPermissions->find($permissionId)->id,  $permissionsOfRoleIds),
                    'nodes' => [],
                ];
                $childpermissions[$permissionId]['nodes'] = self::drawPermissionChildren($permissionId, $allPermissionsID, $isMultiLevel,$allPermissions);
            }
            else{
                $childpermissions[] = $permissionId;
                $childpermissions = array_merge($childpermissions, self::drawPermissionChildren($permissionId, $allPermissionsID, $isMultiLevel,$allPermissions,$permissionsOfRole));
            }
        }
        return $childpermissions;
    }

    public static function getRootPermissions(Array $permissions){
        $arrayOfParents = [];
        $arrayOfParentsNames = [];

        foreach ($permissions as $permission){
            if(is_null($permission->parent)){
                continue;
            }
            if(array_key_exists($permission->parent->name,$arrayOfParentsNames)){
                continue;
            }
            if(!is_null($permission->parent)){
                $arrayOfParents[] = $permission->parent;
                $arrayOfParentsNames[$permission->parent->name] = $permission->parent->name;
            }
        }

        return ($arrayOfParents);
    }

    public static function getAllPermissionsNested(Array $permissions,Array $permissionsOfRole=[],Array $permissionsOfParentRole){
        $permissionsOfRoleIds= array_column($permissionsOfRole, 'id');
        $lastResult = [];
        $rootPermissions = self::getRootPermissions($permissions);

        foreach ($rootPermissions as $rootPermission){
            $result = (object)[];
            $result->label = $rootPermission->name;
            $result->expanded = true;
            $result->checked = in_array($rootPermission->id ?? 0, $permissionsOfRoleIds);
            $nodes = self::getPermissionChildren($rootPermission,$permissionsOfRole,$permissions);
            $nodesArray= [];

            if(is_array($nodes) && count($nodes) > 0){
                foreach ($nodes as $node){
                    $nodesArray[] = $node;
                }
            }

            $result->nodes = $nodesArray;

            $result = (array)$result;
            $lastResult[] = $result;
        }
        return $lastResult;
    }

    public static function loopOverMultiDimentionArray(array $arraysOfNestedPermissions): array
    {

        $array = [];
        $mergedArrays=[];
        foreach ($arraysOfNestedPermissions as $key => $arrayOfNestedPermissions){
            $array2 = [];
            $tempArray = [];
            $tempArray[] = $arrayOfNestedPermissions['label'];
            $tempArray[] = $arrayOfNestedPermissions['checked'];
            $array[] = $tempArray;

            if(!is_null($arrayOfNestedPermissions['nodes']) && count($arrayOfNestedPermissions['nodes']) > 0){
                $array2 = self::loopOverMultiDimentionArray($arrayOfNestedPermissions['nodes']);
                $array = array_merge($array,$array2);

            }

            $mergedArrays = array_merge($array,$array2);

        }
        return ($mergedArrays);


    }


    public static function filterPermissionsAccordingToParentPermissions(Array $parentPermissions,Array $permissions): Array {
        $notAllowedPermissions = array_diff($permissions, $parentPermissions);
        return collect($permissions)->diff($notAllowedPermissions)->all();
    }



//    public static function markRolesPermissionAsChecked(Array $permissionsOfRole,Array &$permissions){
//
//
//        foreach ($permissions as $key => $permission){
//            //here we will check if the permission exists
//            // if exists it will convert the checked from false to true
//            if(){
//                $permission->checked = true;
//            }
//            if(array_key_exists('nodes' ,(array)$permission) && sizeof($permission->nodes )!= 0){
//                self::markRolesPermissionAsChecked($permissionsOfRole,$permissions);
//                if($key % 2 == 0)
//            }
//        }
//
//
//    }


}
