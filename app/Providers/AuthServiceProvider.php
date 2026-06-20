<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('invitar-estudiantes', function (User $user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('read_course_programs', function (User $user) {
            return $user->hasRole('superadmin')
                || $user->abilities()->contains('read_course_programs')
                || $user->abilities()->contains('crud_course_programs');
        });

        Gate::after(function ($user, $ability) {
            if ($user->hasRole('superadmin')) {
                return true;
            }
            return $user->abilities()->contains($ability) ? true : null;
        });
    }
}
