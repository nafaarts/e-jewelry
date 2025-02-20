<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        
        // role access
        Gate::define('ADMIN', fn ($user) => ($user->role == 'ADMIN'));
        Gate::define('SALES', fn ($user) => ($user->role == 'SALES'));
    }
}
