<?php

namespace Cyaxaress\Course\Database\Seeds;

use Illuminate\Database\Seeder;
use Cyaxaress\RolePermissions\Models\Permission;
use Cyaxaress\RolePermissions\Models\Role;

class RolePermissionTableSeeder extends Seeder
{

    public function run()
    {
        foreach (Permission::$permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        foreach (Role::$roles as $name => $permissions) {
            Permission::findOrCreate($name)->givePermissionTo($permissions);
        }

    }
}
