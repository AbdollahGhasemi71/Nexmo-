<?php


namespace Cyaxaress\RolePermissions\Models;


use phpDocumentor\Reflection\Types\Self_;

class Permission extends \Spatie\Permission\Models\Permission
{
    const MANAGE_CATEGORIES = 'manage categories';
    const MANAGE_COURSES = 'manage courses';
    const MANAGE_OWN_COURSES = 'manage own courses';
    const MANEGE_ROLE_PERMISSIONS = 'manage role_permissions';
    const TEACH = 'teach';
    const PERMISSION_SUPERADMIN = 'super admin';

    static $permissions = [
        self::MANAGE_CATEGORIES,
        self::MANEGE_ROLE_PERMISSIONS,
        self::TEACH,
        self::MANAGE_COURSES,
        self::PERMISSION_SUPERADMIN,
        self::MANAGE_OWN_COURSES
    ];
}
