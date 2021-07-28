<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use App\Services\AuthService\IAuthService;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EmailLogin extends Component
{
    use WithRateLimiting;

    public string $email = '';
    public string $password = '';

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => 'required',
    ];

    public function render()
    {
        return view('livewire.auth.email-login');
    }

    public function login(IAuthService $authService)
    {
        $this->validate();

        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->addError('email', "Превышен лимит попыток. Попробуйте через {$exception->secondsUntilAvailable} секунд");

            return;
        }

        $authService->loginByEmail($this->email, $this->password);

        $this->redirect(RouteServiceProvider::HOME);
    }
}
