<?php

namespace Cyaxaress\Category\Policies;

use Cyaxaress\RolePermissions\Models\Permission;
use Cyaxaress\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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
        return $user->hasPermissionTo(Permission::MANAGE_CATEGORIES);

    }
}
