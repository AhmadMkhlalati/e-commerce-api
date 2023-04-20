<?php

namespace Database\Seeders;

use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            $user_1=User::find(1);
            $user_2=User::find(2);

            $permissions=CustomPermission::all()->pluck('name');


            $role= CustomRole::query()->where('name' , 'dev')->where('guard_name', 'web')->first() ?? CustomRole::create(['name' => 'dev','guard_name'=>'web']);

            $role->givePermissionTo($permissions);
            $user_1->assignRole($role);
            $user_2->assignRole($role);



    }
}
