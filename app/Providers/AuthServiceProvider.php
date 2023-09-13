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
            $user = User::find($user->id);
            if (!$user->hasRole('superadmin')) {
                return false;
            }
            return true;
        });

        Gate::after(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });

        Gate::after(function ($user, $ability) {
            return $user->abilities()->contains($ability);
        });
    }
}
