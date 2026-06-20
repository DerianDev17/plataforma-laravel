<?php

namespace App\Providers;

use App\Services\LiveClass\Contracts\LiveClassProvider;
use App\Services\LiveClass\Providers\UnsupportedLiveClassProvider;
use App\Services\LiveClass\Providers\ZoomLiveClassProvider;
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
        if ($this->app->environment('local')) {
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
