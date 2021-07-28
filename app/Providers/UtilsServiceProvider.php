<?php

namespace App\Providers;

use App\Utils\Utils;
use Illuminate\Support\ServiceProvider;

class UtilsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(Utils::class, Utils::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}