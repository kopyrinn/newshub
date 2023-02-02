<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class PackagePolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewPackage', 'managePackage'], $user);
    }
    
    public function view($user, $package)
    {
        return Gate::any(['viewPackage', 'managePackage'], $user, $package);
    }
    
    public function create($user)
    {
        return $user->can('managePackage');
    }
    
    public function update($user, $package)
    {
        return $user->can('managePackage', $package);
    }
    
    public function delete($user, $package)
    {
        return $user->can('managePackage', $package);
    }
    
    public function restore($user, $package)
    {
        return $user->can('managePackage', $package);
    }
    
    public function forceDelete($user, $package)
    {
        return $user->can('managePackage', $package);
    }
}
