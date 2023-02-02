<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class RubricPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewRubric', 'manageRubric'], $user);
    }
    
    public function view($user, $rubric)
    {
        return Gate::any(['viewRubric', 'manageRubric'], $user, $rubric);
    }
    
    public function create($user)
    {
        return $user->can('manageRubric');
    }
    
    public function update($user, $rubric)
    {
        return $user->can('manageRubric', $rubric);
    }
    
    public function delete($user, $rubric)
    {
        return $user->can('manageRubric', $rubric);
    }
    
    public function restore($user, $rubric)
    {
        return $user->can('manageRubric', $rubric);
    }
    
    public function forceDelete($user, $rubric)
    {
        return $user->can('manageRubric', $rubric);
    }
}
