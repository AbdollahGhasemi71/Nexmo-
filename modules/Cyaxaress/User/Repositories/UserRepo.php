<?php


namespace Cyaxaress\User\Repositories;


use Cyaxaress\RolePermissions\Models\Permission;
use Cyaxaress\User\Models\User;

class UserRepo
{
    public function findByEmail($email)
    {
        return User::query()->where('email', $email)->first();
    }

    public function getTeachers()
    {
        return User::permission(Permission::TEACH)->get();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }
}
