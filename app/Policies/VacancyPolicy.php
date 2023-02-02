<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class VacancyPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return Gate::any(['viewVacancy', 'manageVacancy'], $user);
    }
    
    public function view($user, $vacancy)
    {
        return Gate::any(['viewVacancy', 'manageVacancy'], $user, $vacancy);
    }
    
    public function create($user)
    {
        return $user->can('manageVacancy');
    }
    
    public function update($user, $vacancy)
    {
        return $user->can('manageVacancy', $vacancy);
    }
    
    public function delete($user, $vacancy)
    {
        return $user->can('manageVacancy', $vacancy);
    }
    
    public function restore($user, $vacancy)
    {
        return $user->can('manageVacancy', $vacancy);
    }
    
    public function forceDelete($user, $vacancy)
    {
        return $user->can('manageVacancy', $vacancy);
    }
}
