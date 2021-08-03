<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $email = '';
    public string $token = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules()
    {
        return [
            'email' => 'required|email|exists:users',
            'token' => 'required',
            'password' => ['required', Password::defaults(), 'confirmed']
        ];
    }

    public function mount()
    {
        $this->email = (string)request('email');
        $this->token = (string)request('token');
    }

    public function resetPassword()
    {
        $this->validate();

        $status = \Illuminate\Support\Facades\Password::reset(
            [
                'email' => $this->email,
                'token' => $this->token,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            $this->redirect(RouteServiceProvider::HOME);
        } else {
            $this->addError('email', (string)__($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
