<?php

namespace Cyaxaress\Course\tests\Feature;

use Cyaxaress\Course\Database\Seeds\RolePermissionTableSeeder;
use Cyaxaress\Course\Models\Course;
use Cyaxaress\RolePermissions\Models\Permission;
use Cyaxaress\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

//   permitted user can see course index

    public function test_user_can_see_courses_index()
    {
        $this->actAsAdmin();
        $this->get(route('courses.index'))->assertOk();
        $this->actionSuperAdmin();
        $this->get(route('courses.index'))->assertOk();

    }

    public function test_normal_user_can_not_see_courses_index()
    {
        $this->actUser();
        $this->get(route('courses.index'))->assertStatus(403);

    }

//  permitted user can created course
    public function test_permitted_user_can_created_course()
    {
        $this->actAsAdmin();
        $this->get(route('courses.create'))->assertOk();
        $this->actUser();
        auth()->user()->givePermissionTo(Permission::MANAGE_OWN_COURSES);
        $this->get(route('courses.create'))->assertOk();
    }

    public function test_normal_user_can_not_created_course()
    {
        $this->actUser();
        $this->get(route('courses.create'))->assertStatus(403);

    }

//  permitted user can edite course

    public function test_permitted_user_can_edite_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->get(route('courses.edit', $course->id))->assertOk();

        $this->actUser();
        $courses = $this->createCourse();
        auth()->user()->givePermissionTo(Permission::MANAGE_OWN_COURSES);
        $this->get(route('courses.edit', $courses->id))->assertOk();


    }

    public function test_permitted_normal_can_not_edite_course()
    {
        $this->createUser();
        $course = $this->createCourse();
        $this->get(route('courses.edit', $course->id))->assertStatus(403);
        $this->actUser();
        $this->get(route('courses.edit', $course->id))->assertStatus(403);


    }

// permitted user can updated course

//  permitted user can delete course

// permitted user can accepted course
// permitted user can rejected course
// permitted user can lock course


    private function actUser()
    {
        $this->createUser();
    }

    private function actAsAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::MANAGE_COURSES);

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

    private function createCourse()
    {
        return Course::create([
            "teacher_id" => auth()->id(),
            'title' => $this->faker->word,
            "slug" => $this->faker->word,
            "priority" => 12,
            "price" => 4500,
            "percent" => 23,
            "type" => Course::TYPE_FREE,
            "status" => Course::STATUS_NOT_COMPLETED,
            "confirmation_status" => Course::CONFIRMATION_STATUS_ACCEPTED,
        ]);


    }

}
