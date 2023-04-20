<?php

namespace App\Http\Controllers\RolesAndPermissions;

use App\Http\Controllers\MainController;
use App\Http\Requests\RolesAndPermissions\StoreRoleRequest;
use App\Http\Resources\roles\GetAllRolesResource;
use App\Http\Resources\roles\RolesResource;
use App\Http\Resources\roles\SingleRoleResource;
use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use App\Services\RolesAndPermissions\PermissionsServices;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role;

class RolesController extends MainController
{
    const OBJECT_NAME = 'objects.role';
    const relations = ['parent'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {

            $searchKeys = ['id','name'];
            $searchRelationsKeys = ['parent' => ['parent_role' => 'name']];
            return $this->getSearchPaginated(RolesResource::class, CustomRole::class, $request, $searchKeys, self::relations, $searchRelationsKeys);
        }

        return $this->successResponsePaginated(RolesResource::class, CustomRole::class, self::relations);
    }

    /**
     * `Show` the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(StoreRoleRequest $request)
    {
        DB::beginTransaction();

        try {
            $role = CustomRole::create([
                'name' => $request->name,
                'guard_name' => 'web',
                'parent_id' => $request->parent_role
            ]);

            $flattenPermissions = [];

            foreach ($request->permissions as $permission) {
                $innerFlattenPermissions = PermissionsServices::loopOverMultiDimentionArray($permission['tree']) ?? [];
                $flattenPermissions = array_merge($innerFlattenPermissions, $flattenPermissions);
            }
            $flattenPermissions = collect($flattenPermissions)->unique();

            $approvedPermissions = array_filter($flattenPermissions->toArray(), fn($value) => $value[1]);
            $approvedPermissions = (collect($approvedPermissions)->pluck(0));
            $role->givePermissionTo($approvedPermissions);

            DB::commit();
            return $this->successResponse(
                __('messages.success.create', ['name' => __(self::OBJECT_NAME)]),
                [
                    'role' => new SingleRoleResource($role)
                ]
            );

        } catch (\Exception|QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.create', ['name' => __(self::OBJECT_NAME)]),
                'error' => $e->getMessage()
            ]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param CustomRole $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, CustomRole $role)
    {
        $permissionsOfRole = CustomRole::find($role->id) ? CustomRole::findOrFail($role->id)->permissions->toArray() : [];

        $parentId = null;
        if ($role->parent) {
            $parentId = $role->parent->id ?? null;
        }
        $permissionsForParentRoleIds = CustomRole::find($parentId) ? CustomRole::findOrFail($parentId)->permissions->pluck('id')->toArray() : [];
        $allPermissionsWithCheck = [];
        $permissions = CustomPermission::with('parent')->get();
        foreach ($permissions as $permission) {
            if (in_array($permission->id, $permissionsForParentRoleIds)) {
                $allPermissionsWithCheck[] = $permission;
            }
        }

        //        print_r(collect($allPermissionsWithCheck)->pluck('name'));
        $permissions = [];
        $nestedPermissions = PermissionsServices::getAllPermissionsNested($allPermissionsWithCheck, $permissionsOfRole, $permissionsForParentRoleIds);
        foreach ($nestedPermissions as $rootPermission) {
            $tempArray = [];
            $tempArray['id'] = uniqid();
            $tempArray['name'] = $rootPermission['label'];
            $tempArray['tree'] = [$rootPermission];

            $permissions[] = $tempArray;
        }


        return $this->successResponse(
            'Success!',
            [
                'role' => (new SingleRoleResource($role))->permissions($permissions),
            ],
            202

        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreRoleRequest $request, CustomRole $role)
    {
        DB::beginTransaction();

        try {
            $role->update([
                'name' => $request->name,
                'parent_id' => $request->parent_role
            ]);

            $flattenPermissions = [];
            //@TODO: add validation to filter permissions that are added to the role but the role does n't have the rights to do it
            //plucked the name of flatten permissions
//            $childPermissions = collect($flattenPermissions)->pluck(0)->toArray();
//            $parentPermissions = CustomRole::findById($request->parent_id)->permissions->pluck('name')->toArray();
//
//            $filteredPermissions = PermissionsServices::filterPermissionsAccordingToParentPermissions($parentPermissions,$childPermissions);
//

            foreach ($request->permissions as $permission) {
                $innerFlattenPermissions = PermissionsServices::loopOverMultiDimentionArray($permission['tree']) ?? [];
                $flattenPermissions = array_merge($innerFlattenPermissions, $flattenPermissions);
            }
            $flattenPermissions = collect($flattenPermissions)->unique();

            $allPermissionsNames = CustomPermission::all()->pluck('name')->toArray();
            $approvedPermissions = array_filter($flattenPermissions->toArray(), fn($value) => $value[1]);
            $approvedPermissions = (collect($approvedPermissions)->pluck(0));

            $role->revokePermissionTo($allPermissionsNames);
            $role->givePermissionTo($approvedPermissions);

            DB::commit();

            return $this->successResponse(
                __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
                [
                    'role' => new SingleRoleResource($role)
                ]
            );

        } catch (\Exception|QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)]),
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomRole $role
     * @return \Illuminate\Http\JsonResponse
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomRole $role)
    {
        $message = '';
        if (!$role->canDeleteRole($message)) {
            return $this->errorResponse($message);
        }


        if ($role->delete()) {
            return $this->successResponse(
                __('messages.success.delete', ['name' => __(self::OBJECT_NAME)]),
                [
                    'role' => new SingleRoleResource($role)
                ]
            );
        }
        return $this->errorResponse(__('messages.failed.delete', ['name' => __(self::OBJECT_NAME)]));
    }

    public function getNestedPermissionsForRole(Request $request)
    {

        $request->validate([
            'role' => 'nullable|integer',
            'parent_role' => 'nullable|integer'
        ]);

        $permissionsOfRole = CustomRole::find($request->role) ? CustomRole::findOrFail($request->role)->permissions->toArray() : [];
        $permissionsForParentRoleIds = CustomRole::find($request->parent_role) ? CustomRole::findOrFail($request->parent_role)->permissions->pluck('id')->toArray() : [];
        $allPermissionsWithCheck = [];
        $permissions = CustomPermission::with('parent')->get();
        foreach ($permissions as $permission) {
            if (in_array($permission->id, $permissionsForParentRoleIds)) {
                $allPermissionsWithCheck[] = $permission;
            }
        }

        //        print_r(collect($allPermissionsWithCheck)->pluck('name'));
        $returnArray = [];
        $nestedPermissions = PermissionsServices::getAllPermissionsNested($allPermissionsWithCheck, $permissionsOfRole, $permissionsForParentRoleIds);
        foreach ($nestedPermissions as $rootPermission) {
            $tempArray = [];
            $tempArray['id'] = uniqid();
            $tempArray['name'] = $rootPermission['label'];
            $tempArray['tree'] = [$rootPermission];

            $returnArray[] = $tempArray;
        }

        return $this->successResponse('success!', $returnArray);

    }

    public function getAllRoles()
    {
        return $this->successResponse('Success!', ["roles" => GetAllRolesResource::collection(CustomRole::all())]);
    }

    public function getTableHeaders()
    {
        return $this->successResponse('Success!', ['headers' => __('headers.roles')]);
    }
}
