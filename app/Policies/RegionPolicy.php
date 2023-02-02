<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class RegionPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewRegion', 'manageRegion'], $user);
    }
    
    public function view($user, $region)
    {
        return Gate::any(['viewRegion', 'manageRegion'], $user, $region);
    }
    
    public function create($user)
    {
        return $user->can('manageRegion');
    }
    
    public function update($user, $region)
    {
        return $user->can('manageRegion', $region);
    }
    
    public function delete($user, $region)
    {
        return $user->can('manageRegion', $region);
    }
    
    public function restore($user, $region)
    {
        return $user->can('manageRegion', $region);
    }
    
    public function forceDelete($user, $region)
    {
        return $user->can('manageRegion', $region);
    }
}
