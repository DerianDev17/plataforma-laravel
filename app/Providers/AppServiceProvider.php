<?php

namespace App\Providers;

use App\Services\Attendance\Contracts\AttendancePolicy;
use App\Services\Attendance\Policies\DefaultAttendancePolicy;
use App\Services\Audit\Contracts\AuditLogger;
use App\Services\Audit\Loggers\DatabaseAuditLogger;
use App\Services\LiveClass\Contracts\LiveClassProvider;
use App\Services\LiveClass\Providers\UnsupportedLiveClassProvider;
use App\Services\LiveClass\Providers\ZoomLiveClassProvider;
use App\Services\Payment\Contracts\PaymentAccessResolver;
use App\Services\Payment\Resolvers\DefaultPaymentAccessResolver;
use App\Services\Registration\Contracts\UserRegistrar;
use App\Services\Registration\Registrars\StudentUserRegistrar;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local') && config('telescope.enabled', false)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(LiveClassProvider::class, function ($app) {
            $provider = config('services.live_classes.provider', 'zoom');

            switch ($provider) {
                case 'zoom':
                    return $app->make(ZoomLiveClassProvider::class);
                default:
                    return new UnsupportedLiveClassProvider($provider);
            }
        });

        $this->app->bind(AuditLogger::class, DatabaseAuditLogger::class);

        $this->app->bind(PaymentAccessResolver::class, DefaultPaymentAccessResolver::class);

        $this->app->bind(UserRegistrar::class, StudentUserRegistrar::class);

        $this->app->bind(AttendancePolicy::class, DefaultAttendancePolicy::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
