<?php

namespace App\Providers;

use App\Repositories\UserRepository\IUserRepository;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}