<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::prefix('login')->group(function () {
        Route::get('/', [AuthenticationController::class, 'showLoginPage'])
            ->name('auth.login-form');

        Route::post('/', [AuthenticationController::class, 'loginViaEmail'])
            ->middleware('throttle:login')
            ->name('auth.login-via-email');
    });

    Route::prefix('verification-email')->group(function () {
        Route::get('/', [VerificationEmailController::class, 'showPageWithSendButton'])
            ->name('verification-email.index');

        Route::get('verify', [VerificationEmailController::class, 'verifyEmail'])
            ->middleware('signed')
            ->name('verification-email.verify');

        Route::post('send', [VerificationEmailController::class, 'sendVerificationLink'])
            ->name('verification-email.send-link');
    });

    Route::prefix('registration')->group(function () {
        Route::get('/', [RegistrationController::class, 'showPageWithForm'])
            ->middleware('guest')
            ->name('registration.index');

        Route::post('/', [RegistrationController::class, 'createNewUser'])
            ->middleware('guest')
            ->name('registration.create');
    });

    Route::prefix('reset-password')->group(function () {
        Route::get('/', [ResetPasswordController::class, 'showPageWithEmailForm'])
            ->middleware('guest')
            ->name('reset-password.forgot');

        Route::post('/', [ResetPasswordController::class, 'sendResetLink'])
            ->middleware('guest')
            ->name('reset-password.send-link');

        Route::get('/new-password', [ResetPasswordController::class, 'showPageWithResetForm'])
            ->middleware('guest')
            ->name('password.reset');

        Route::post('/new-password', [ResetPasswordController::class, 'saveNewPassword'])
            ->name('reset-password.save-new-password');
    });


    Route::prefix('socialite')->group(function () {
        Route::get('google', [GoogleController::class, 'index'])
            ->name('login.socialite.google');
    });

    Route::prefix('callback')->group(function () {
        Route::get('google', [GoogleController::class, 'callback']);
    });
});
