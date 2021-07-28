<?php

namespace App\Providers;

use App\Services\AuthService\AuthService;
use App\Services\AuthService\IAuthService;
use Illuminate\Support\ServiceProvider;

class AppAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(IAuthService::class, AuthService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}