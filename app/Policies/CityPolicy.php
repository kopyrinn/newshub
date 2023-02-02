<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class CityPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewCity', 'manageCity'], $user);
    }
    
    public function view($user, $city)
    {
        return Gate::any(['viewCity', 'manageCity'], $user, $city);
    }
    
    public function create($user)
    {
        return $user->can('manageCity');
    }
    
    public function update($user, $city)
    {
        return $user->can('manageCity', $city);
    }
    
    public function delete($user, $city)
    {
        return $user->can('manageCity', $city);
    }
    
    public function restore($user, $city)
    {
        return $user->can('manageCity', $city);
    }
    
    public function forceDelete($user, $city)
    {
        return $user->can('manageCity', $city);
    }
}
