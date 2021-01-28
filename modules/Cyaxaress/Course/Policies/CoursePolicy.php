<?php

namespace Cyaxaress\Course\Policies;

use Cyaxaress\RolePermissions\Models\Permission;
use Cyaxaress\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function menege(User $user)
    {
        return $user->hasPermissionTo(Permission::MANAGE_COURSES);
    }

    public function create($user)
    {
        return $user->hasPermissionTo(Permission::MANAGE_COURSES) ||
            $user->hasPermissionTo(Permission::MANAGE_OWN_COURSES);
    }

    public function edit($user, $course)
    {
        if ($user->hasPermissionTo(Permission::MANAGE_COURSES)) return true;
        return $user->hasPermissionTo
            (Permission::MANAGE_OWN_COURSES) && $course->teacher_id == $user->id;
    }

    public function delete($user)
    {
        if ($user->hasPermissionTo(Permission::MANAGE_COURSES)) return true;
    }

    public function change_confirmation_status($user)
    {
        if ($user->hasPermissionTo(Permission::MANAGE_COURSES)) return true;
    }
}
