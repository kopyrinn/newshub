<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class UserCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewUserCategory', 'manageUserCategory'], $user);
    }
    
    public function view($user, $userCategory)
    {
        return Gate::any(['viewUserCategory', 'manageUserCategory'], $user, $userCategory);
    }
    
    public function create($user)
    {
        return $user->can('manageUserCategory');
    }
    
    public function update($user, $userCategory)
    {
        return $user->can('manageUserCategory', $userCategory);
    }
    
    public function delete($user, $userCategory)
    {
        return $user->can('manageUserCategory', $userCategory);
    }
    
    public function restore($user, $userCategory)
    {
        return $user->can('manageUserCategory', $userCategory);
    }
    
    public function forceDelete($user, $userCategory)
    {
        return $user->can('manageUserCategory', $userCategory);
    }
}
