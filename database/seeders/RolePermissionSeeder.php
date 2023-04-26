<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        Role::truncate();
        Permission::truncate();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::create(['name' => "Administrator"]);

        $permissions = [
            'Is Admin',
            'View All Users'
        ];

        foreach ($permissions as $permission){
            Permission::create(['name' => $permission]);
            $role->givePermissionTo($permission);
        }

        $user = User::find(1);
        if($user){
            $user->assignRole($role);
        }
    }
}
