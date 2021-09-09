<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function showLoginPage(): Factory|View|Application
    {
        return view('auth.login');
    }

    public function loginViaEmail(Request $request): Redirector|Application|RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email|exists:users',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only(['email', 'password']), true)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return redirect()->back()->withErrors([
            'email' => __('Invalid email or password')
        ]);
    }
}