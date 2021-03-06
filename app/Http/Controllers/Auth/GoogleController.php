<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService\IAuthService;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SocialiteRedirectResponse;

class GoogleController extends Controller
{
    public function index(): SocialiteRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(IAuthService $authService): RedirectResponse
    {
        $authService->loginBySocialiteUser(
            Socialite::driver('google')->user(),
            'google'
        );

        return redirect()->route('dashboard');
    }
}