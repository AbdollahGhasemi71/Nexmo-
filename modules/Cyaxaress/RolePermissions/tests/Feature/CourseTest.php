<?php

namespace Cyaxaress\RolePermissions\tests\Feature;


use Cyaxaress\Course\Database\Seeds\RolePermissionTableSeeder;
use Cyaxaress\RolePermissions\Models\Permission;
use Cyaxaress\RolePermissions\Models\Role;
use Cyaxaress\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_permiited_user_can_see_index()
    {
        $this->actUser();
        $this->actAsAdmin();
        $this->get(route('role-permissions.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_index()
    {
        $this->actUser();
        $this->get(route('role-permissions.index'))->assertStatus(403);
    }

    public function test_permiited_user_can_store_roles()
    {

        $this->actAsAdmin();
        $this->post(route('role-permissions.store'), [
            "name" => 'article',
            "permissions" => [
                Permission::MANAGE_COURSES,
                Permission::TEACH
            ]
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals(1, Role::count());
    }

    public function test_normal_user_can_not_store_roles()
    {

        $this->actUser();
        $this->post(route('role-permissions.store'), [
            "name" => 'article',
            "permissions" => [
                Permission::MANAGE_COURSES,
                Permission::TEACH
            ]
        ])->assertStatus(403);

    }

    public function test_permiited_user_can_see_edit()
    {
        $this->actAsAdmin();
        $role = $this->createRole();
        $this->get(route('role-permissions.edit', $role->id))->assertOk();
    }

    public function test_normal_user_can_not_see_edit()
    {

        $this->actUser();
        $role = $this->createRole();
        $this->get(route('role-permissions.edit', $role->id))->assertStatus(403);
    }

    public function test_permiited_user_can_update_roles()
    {
        $this->actAsAdmin();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update', $role->id), [
            "name" => 'articleghasmei',
            "id" => $role->id,
            "permissions" => [
                Permission::MANAGE_COURSES,
            ]
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals('articleghasmei', $role->fresh()->name);

    }

    public function test_normal_user_can_not_update_roles()
    {

        $this->actUser();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update', $role->id), [
            "name" => 'articleghasmei',
            "id" => $role->id,
            "permissions" => [
                Permission::MANAGE_COURSES,
            ]
        ])->assertStatus(403);
        $this->assertEquals($role->name, $role->fresh()->name);

    }

    public function test_permiited_user_can_see_delete()
    {
        $this->actAsAdmin();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy', $role->id))->assertOk();
        $this->assertEquals(0, $role->count());
    }

    public function test_normal_user_can_not_see_delete()
    {

        $this->actUser();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy', $role->id))->assertStatus(403);

    }


    private function actUser()
    {
        $this->createUser();
    }

    public function actAsAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::MANEGE_ROLE_PERMISSIONS);

    }

    private function actionSuperAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPERADMIN);
    }


    private function createUser()
    {
        $this->actingAs(factory(User::class)->create());
        $this->seed(RolePermissionTableSeeder::class);
    }

    private function createRole()
    {
        return Role::create(['name' => 'category_name',])->syncPermissions([
            Permission::MANAGE_COURSES,
            Permission::TEACH
        ]);


    }


}
