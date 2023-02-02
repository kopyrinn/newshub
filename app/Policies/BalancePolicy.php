<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class BalancePolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewBalance', 'manageBalance'], $user);
    }
    
    public function view($user, $balance)
    {
        return Gate::any(['viewBalance', 'manageBalance'], $user, $balance);
    }
    
    public function create($user)
    {
        return $user->can('manageBalance');
    }
    
    public function update($user, $balance)
    {
        return $user->can('manageBalance', $balance);
    }
    
    public function delete($user, $balance)
    {
        return $user->can('manageBalance', $balance);
    }
    
    public function restore($user, $balance)
    {
        return $user->can('manageBalance', $balance);
    }
    
    public function forceDelete($user, $balance)
    {
        return $user->can('manageBalance', $balance);
    }
}
