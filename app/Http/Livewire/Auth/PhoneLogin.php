<?php

namespace App\Http\Livewire\Auth;

use App\Exceptions\InvalidVerificationCodeException;
use App\Jobs\SendVerificationSmsJob;
use App\Providers\RouteServiceProvider;
use App\Repositories\UserRepository\IUserRepository;
use App\Rules\PhoneNumberRule;
use App\Services\AuthService\IAuthService;
use App\Services\SmsService\ISmsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class PhoneLogin extends Component
{
    public string $phoneNumber = '';
    public string $code = '';
    public string $email = '';
    public bool $showCodeForm = false;
    public ?Carbon $lastRequestedAt = null;
    public bool $showEmailForm = false;
    public int $timeoutInSeconds = 60;

    public function mount()
    {
        if (App::isLocal()) {
            $this->timeoutInSeconds = 3;
            $this->phoneNumber = '+7-913-794-4814';
        }
    }

    protected function getRules(): array
    {
        return [
            'phoneNumber' => ['required', new PhoneNumberRule()],
            'code' => 'required',
            'email' => 'required|email',
        ];
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.auth.phone-login');
    }

    public function sendCode(ISmsService $smsService)
    {
        $this->validateOnly('phoneNumber');

        $this->lastRequestedAt = now();
        $this->showCodeForm = true;

        dispatch(new SendVerificationSmsJob($this->phoneNumber, $smsService));
    }

    public function verifyCode(ISmsService $smsService, IUserRepository $userRepository, IAuthService $authService)
    {
        $this->validateOnly('phoneNumber');
        $this->validateOnly('code');

        try {
            $smsService->verifyCode($this->phoneNumber, $this->code);

            if ($user = $userRepository->findByPhone($this->phoneNumber)) {
                $authService->loginWithUser($user);

                $this->redirect(RouteServiceProvider::HOME);
            } else {
                $this->showEmailForm = true;
            }
        } catch (InvalidVerificationCodeException $exception) {
            $this->addError('code', $exception->getMessage());
        } catch (Exception $exception) {
            session()->flash('criticalError', $exception->getMessage());
        }
    }

    public function registerEmailAndLogin(IAuthService $authService)
    {
        $this->validate();

        $authService->registerAndLoginWithPhoneAndEmail($this->email, $this->phoneNumber);

        $this->redirectRoute(RouteServiceProvider::HOME);
    }

    public function getNextTryingSecondsIntervalProperty(): int
    {
        if ($this->lastRequestedAt === null) {
            return 0;
        }

        $controlTime = $this->lastRequestedAt->clone()->addSeconds($this->timeoutInSeconds);

        if ($controlTime->lessThan(now())) {
            return 0;
        }

        return $controlTime->diffInSeconds(now());
    }
}
