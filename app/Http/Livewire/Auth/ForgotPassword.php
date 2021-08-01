<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';
    public string $successMessage = '';

    protected $rules = [
        'email' => 'required|email|exists:users',
    ];

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status == Password::RESET_LINK_SENT) {
            $this->successMessage = (string)__($status);
        } else {
            $this->addError('email', (string)__($status));
        }
    }
}
