<?php

namespace Cyaxaress\Course\tests\Feature;

use Cyaxaress\Category\Models\Category;
use Cyaxaress\Course\Database\Seeds\RolePermissionTableSeeder;
use Cyaxaress\Course\Models\Course;
use Cyaxaress\RolePermissions\Models\Permission;
use Cyaxaress\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
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


    public function test_permitted_user_can_not_edite_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->get(route('courses.edit', $course->id))->assertOk();

        $this->actUser();
        auth()->user()->givePermissionTo(Permission::MANAGE_OWN_COURSES);
        $this->get(route('courses.edit', $course->id))->assertStatus(403);

    }

    public function test_permitted_normal_can_not_edite_course()
    {
        $this->createUser();
        $course = $this->createCourse();
        $this->get(route('courses.edit', $course->id))->assertStatus(403);
        $this->actUser();
        $this->get(route('courses.edit', $course->id))->assertStatus(403);


    }

    public function test_user_normal_can_see_not_edit_course()
    {
        $this->actUser();
        $course = $this->createCourse();
        $this->get(route('courses.edit', $course->id))->assertStatus(403);
    }

    //    permitted user can store in the course

    public function test_permitted_user_can_stor_on_course()
    {

        $this->actUser();
        auth()->user()->givePermissionTo(Permission::MANAGE_OWN_COURSES, Permission::TEACH);
        Storage::fake('local');
        $response = $this->post(route('courses.store'), $this->courseData());
        $response->assertRedirect(route('courses.index'));
        $this->assertEquals($this->count(), 1);


    }

// permitted user can updated course

    public function test_permitted_user_can_update_course()
    {
        $this->actUser();
        auth()->user()->givePermissionTo(Permission::MANAGE_OWN_COURSES, Permission::TEACH);
        $course = $this->createCourse();
        $this->patch(route('courses.update', $course->id), $this->courseUpdate())
            ->assertRedirect(route('courses.index'));
        $course = $course->fresh();
        self::assertEquals('update title', $course->title);
    }

    public function test_normal_permitted_user_can_not_update_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->actUser();
        auth()->user()->givePermissionTo(Permission::TEACH);
        $this->patch(route('courses.update', $course->id), $this->courseUpdate())
            ->assertStatus(403);

    }

//  permitted user can delete course
    public function test_permitted_user_can_delete_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->delete(route('courses.destroy', $course->id))->assertOk();
        $this->assertEquals(0, Course::count());
    }

    public function test_normal_user_can_not_delete_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->actUser();
        $this->delete(route('courses.destroy', $course->id))->assertStatus(403);
        $this->assertEquals(1, Course::count());
    }

// permitted user can accepted course
    public function test_permitted_user_can_accepted_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->patch(route('courses.accept', $course->id))->assertOk();
        $this->patch(route('courses.reject', $course->id))->assertOk();
        $this->patch(route('courses.lock', $course->id))->assertOk();
    }

    public function test_normal_user_can_not_accepted_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();

        $this->actUser();
        $this->patch(route('courses.accept', $course->id))->assertStatus(403);
        $this->patch(route('courses.reject', $course->id))->assertStatus(403);
        $this->patch(route('courses.lock', $course->id))->assertStatus(403);
    }


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
            "confirmation_status" => Course::CONFIRMATION_STATUS_PENDING

        ]);
    }

    private function createCategory()
    {
        return Category::create(['title' => $this->faker->word, 'slug' => $this->faker->word]);
    }

    private function courseData()
    {
        $category = $this->createCategory();
        return [
            "teacher_id" => auth()->id(),
            "category_id" => $category->id,
            'title' => $this->faker->sentence(2),
            "slug" => $this->faker->sentence(2),
            "priority" => 12,
            "price" => 4500,
            "percent" => 23,
            "type" => Course::TYPE_FREE,
            "image" => UploadedFile::fake()->image('banner.jpg'),
            "status" => Course::STATUS_NOT_COMPLETED,
        ];
    }

    private function courseUpdate()
    {
        $category = $this->createCategory();
        return [
            "teacher_id" => auth()->id(),
            "category_id" => $category->id,
            'title' => "update title",
            "slug" => "update slug",
            "priority" => 12,
            "price" => 4500,
            "percent" => 23,
            "type" => Course::TYPE_FREE,
            "image" => UploadedFile::fake()->image('banner.jpg'),
            "status" => Course::STATUS_NOT_COMPLETED,
        ];

    }

}
