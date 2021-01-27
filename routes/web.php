<?php

use Cyaxaress\RolePermissions\Models\Permission;
Route::get('/', function () {
    return view('index');
});


Route::get('/test', function () {
    auth()->user()->givePermissionTo(Permission::PERMISSION_SUPERADMIN);
    return auth()->user()->permissions;
});
