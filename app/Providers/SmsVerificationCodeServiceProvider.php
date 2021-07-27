<?php

namespace App\Providers;

use App\Services\SmsService\FakeSmsSender;
use App\Services\SmsService\ISmsSender;
use App\Services\SmsService\ISmsService;
use App\Services\SmsService\SmsService;
use App\Services\SmsService\TelesignSmsSender;
use App\Services\VerificationCodeService\CodeGenerator;
use App\Services\VerificationCodeService\ICodeGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class SmsVerificationCodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(ISmsService::class, SmsService::class);
        $this->app->singleton(ICodeGenerator::class, CodeGenerator::class);

        if (App::isLocal()) {
            $this->app->singleton(ISmsSender::class, FakeSmsSender::class);
        } else {
            $this->app->singleton(ISmsSender::class, TelesignSmsSender::class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}