<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class WidgetPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewWidget', 'manageWidget'], $user);
    }
    
    public function view($user, $widget)
    {
        return Gate::any(['viewWidget', 'manageWidget'], $user, $widget);
    }
    
    public function create($user)
    {
        return $user->can('manageWidget');
    }
    
    public function update($user, $widget)
    {
        return $user->can('manageWidget', $widget);
    }
    
    public function delete($user, $widget)
    {
        return $user->can('manageWidget', $widget);
    }
    
    public function restore($user, $widget)
    {
        return $user->can('manageWidget', $widget);
    }
    
    public function forceDelete($user, $widget)
    {
        return $user->can('manageWidget', $widget);
    }
}
