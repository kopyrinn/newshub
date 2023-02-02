<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class GrammaticalErrorPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewGrammaticalError', 'manageGrammaticalError'], $user);
    }
    
    public function view($user, $grammaticalError)
    {
        return Gate::any(['viewGrammaticalError', 'manageGrammaticalError'], $user, $grammaticalError);
    }
    
    public function create($user)
    {
        return $user->can('manageGrammaticalError');
    }
    
    public function update($user, $grammaticalError)
    {
        return $user->can('manageGrammaticalError', $grammaticalError);
    }
    
    public function delete($user, $grammaticalError)
    {
        return $user->can('manageGrammaticalError', $grammaticalError);
    }
    
    public function restore($user, $grammaticalError)
    {
        return $user->can('manageGrammaticalError', $grammaticalError);
    }
    
    public function forceDelete($user, $grammaticalError)
    {
        return $user->can('manageGrammaticalError', $grammaticalError);
    }
}
