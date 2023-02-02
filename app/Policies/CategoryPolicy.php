<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewCategory', 'manageCategory'], $user);
    }
    
    public function view($user, $category)
    {
        return Gate::any(['viewCategory', 'manageCategory'], $user, $category);
    }
    
    public function create($user)
    {
        return $user->can('manageCategory');
    }
    
    public function update($user, $category)
    {
        return $user->can('manageCategory', $category);
    }
    
    public function delete($user, $category)
    {
        return $user->can('manageCategory', $category);
    }
    
    public function restore($user, $category)
    {
        return $user->can('manageCategory', $category);
    }
    
    public function forceDelete($user, $category)
    {
        return $user->can('manageCategory', $category);
    }
}
