<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Silvanite\Brandenburg\Traits\ValidatesPermissions;

class AuthServiceProvider extends ServiceProvider
{
    use ValidatesPermissions;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Post::class => \App\Policies\PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        collect([
            'viewGrammaticalError',
            'manageGrammaticalError',
            'viewAd',
            'manageAd',
            'viewBalance',
            'manageBalance',
            'viewCategory',
            'manageCategory',
            'viewCity',
            'manageCity',
            'viewPackage',
            'managePackage',
            'viewPage',
            'managePage',
            'viewPost',
            'managePost',
            'viewRegion',
            'manageRegion',
            'viewRubric',
            'manageRubric',
            'viewUserCategory',
            'manageUserCategory',
            'viewVacancy',
            'manageVacancy',
            'viewWidget',
            'manageWidget',
        ])->each(function ($permission) {
            Gate::define($permission, function ($user) use ($permission) {
                if ($this->nobodyHasAccess($permission)) {
                    return true;
                }

                return $user->hasRoleWithPermission($permission);
            });
        });

        $this->registerPolicies();
    }
}
