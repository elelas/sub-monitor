<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Livewire\Component;

class VerifyEmail extends Component
{
    public string $email = '';
    public string $successMessage = '';

    protected $rules = [
        'email' => 'required',
    ];

    public function mount()
    {
        if (request()->user()->hasVerifiedEmail()) {
            $this->redirect(RouteServiceProvider::HOME);
        }

        $this->email = request()->user()?->email ?? '';
    }

    public function sendVerifyEmail()
    {
        $this->validate();

        if (request()->user()->hasVerifiedEmail()) {
            $this->redirect(RouteServiceProvider::HOME);
        }

        request()->user()->sendEmailVerificationNotification();

        $this->successMessage = (string)__('verification-link-sent');
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
