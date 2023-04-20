<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\MainController;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\User\SingleUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends MainController
{

    const OBJECT_NAME = 'objects.user';

    public function index(Request $request){

        if ($request->method()=='POST') {
            $data=$request->data ?? [];
            $roleName = $request->data['role_name'] ?? '';
            $keys = array_keys($data ?? []);
            $searchKey = ['first_name','last_name','email','username'];
            $rows=User::with(['roles' => fn($query) => $query->select('name')])
                ->whereHas('roles',fn ($query) => $query->whereRaw('lower(name) like (?)',["%$roleName%"]))
                ->where(function($query) use($data,$keys,$searchKey){
                        foreach($keys as $key) if(in_array($key,$searchKey))
                            $query->where($key,'LIKE', '%'.$data[$key].'%');
                })
                ->paginate($request->limit ?? config('defaults.default_pagination'));


                return  UserResource::collection($rows);

        }

        return $this->successResponse('Success',[UserResource::collection(User::with('roles')->paginate(config('defaults.default_pagination')))]);

    }

    public function store(StoreUserRequest $request){
        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'salt' => $request->salt ?? '',
                'is_active' => (bool)$request->is_active,
                'password' => Hash::make($request->password),
            ]);
            $user->AssignRole($request->role_id);
            DB::commit();

                 return $this->successResponse(
                     __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                 [
                    'user' => new SingleUserResource($user),
                 ]
                 );
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->errorResponse();
        }
    }

    public function show(User $user)
    {
        return $this->successResponse(
            'Success!',
            ['user' => new SingleUserResource($user)]
        );

    }

    public function update(UpdateUserRequest $request, User $user)
    {

        $user->username =  $request->username;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name =$request->last_name;
        $user->is_active = $request->is_active;
        $user->salt = $request->salt ?? '123';

        if(count($user->roles) > 0){
            $userOldRoles = $user->roles->pluck('name')->toArray()[0];
            $user->removeRole($userOldRoles);
        }

        $role = CustomRole::findOrFail($request->role_id);
        $user->assignRole($role);


        if(!($user->save()))
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            [
                'user' => new SingleUserResource($user)
            ]
        );
    }

    public function destroy(User $user)
    {
        if(!$user->delete())
            return $this->errorResponse(
                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            [
                'user' => new SingleUserResource($user)
            ]
        );

    }

//    public function toggleStatus(Request $request ,$id){
//
//        $request->validate([
//            'is_disabled' => 'boolean|required'
//        ]);
//
//        $user = User::findOrFail($id);
//        $user->is_disabled=$request->is_disabled;
//        if(!$user->save())
//            return $this->errorResponse(
//                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
//            );
//
//        return $this->successResponse(
//            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
//            [
//                'user' =>  new UserResource($user)
//            ]
//        );
//
//    }
    public function getTableHeaders(){
        return $this->successResponse('Success',['headers' => __('headers.users') ]);
}
}
