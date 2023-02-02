<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class PagePolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewPage', 'managePage'], $user);
    }
    
    public function view($user, $page)
    {
        return Gate::any(['viewPage', 'managePage'], $user, $page);
    }
    
    public function create($user)
    {
        return $user->can('managePage');
    }
    
    public function update($user, $page)
    {
        return $user->can('managePage', $page);
    }
    
    public function delete($user, $page)
    {
        return $user->can('managePage', $page);
    }
    
    public function restore($user, $page)
    {
        return $user->can('managePage', $page);
    }
    
    public function forceDelete($user, $page)
    {
        return $user->can('managePage', $page);
    }
}
