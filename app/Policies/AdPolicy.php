<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class AdPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewAd', 'manageAd'], $user);
    }
    
    public function view($user, $ad)
    {
        return Gate::any(['viewAd', 'manageAd'], $user, $ad);
    }
    
    public function create($user)
    {
        return $user->can('manageAd');
    }
    
    public function update($user, $ad)
    {
        return $user->can('manageAd', $ad);
    }
    
    public function delete($user, $ad)
    {
        return $user->can('manageAd', $ad);
    }
    
    public function restore($user, $ad)
    {
        return $user->can('manageAd', $ad);
    }
    
    public function forceDelete($user, $ad)
    {
        return $user->can('manageAd', $ad);
    }
}
