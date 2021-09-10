<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    public function showPageWithEmailForm(): Factory|View|Application
    {
        return view('auth.forgot-password');
    }

    public function showPageWithResetForm(Request $request): Factory|View|Application
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|exists:users'
        ]);

        $this->checkToken($request->get('email'), $request->get('token'));

        return view('auth.reset-password');
    }

    public function sendResetLink(Request $request): Redirector|Application|RedirectResponse
    {
        $request->validate([
            'email' => 'required'
        ]);

        $result = Password::sendResetLink(['email' => $request->get('email')]);

        return redirect()->back()->with('status', __($result));
    }

    public function saveNewPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|exists:users',
            'password' => ['required', 'confirmed', PasswordRule::defaults()]
        ]);

        $this->checkToken($request->get('email'), $request->get('token'));

        $user = User::whereEmail($request->get('email'))->firstOrFail();

        $user->update([
            'password' => Hash::make($request->get('password'))
        ]);

        Password::deleteToken($user);

        return redirect()->route('auth.login-form')->with('status', __('Password was reset'));
    }

    private function checkToken(string $email, string $token)
    {
        if (!Password::tokenExists(User::whereEmail($email)->firstOrFail(), $token)) {
            abort(404);
        }
    }
}