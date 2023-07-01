<?php

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{
    public function retrieved(Role $role)
    {
        $permissions = $role->getPermissions()->pluck('permission_slug')->flip();

        if ($permissions) {
            $role->selected_permissions = $permissions->map(function($item) {
                return true;
            })->toArray();
        } else {
            $role->selected_permissions = [];
        }
    }

    public function updating(Role $role)
    {
        if ($role->selected_permissions) {
            $role->setPermissions(array_keys(array_filter($role->selected_permissions)));
            unset($role->selected_permissions);
        }
    }

    public function created(Role $role)
    {
        if ($role->selected_permissions) {
            $role->setPermissions(array_keys(array_filter($role->selected_permissions)));
            unset($role->selected_permissions);
        }
    }
}
