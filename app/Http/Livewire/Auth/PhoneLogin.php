<?php

namespace App\Http\Livewire\Auth;

use App\Jobs\SendVerificationSmsJob;
use App\Rules\PhoneNumberRule;
use App\Services\SmsService\ISmsService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PhoneLogin extends Component
{
    public string $phoneNumber = '+7-913-794-4814';
    public string $code = '';
    public bool $codeRequested = false;
    public ?Carbon $lastRequestedAt = null;

    protected function getRules()
    {
        return [
            'phoneNumber' => ['required', new PhoneNumberRule()],
            'code' => 'required',
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
        $this->codeRequested = true;

        dispatch(new SendVerificationSmsJob($this->phoneNumber, $smsService));
    }

    public function verifyCode(ISmsService $smsService)
    {
        $this->validate();

        $result = $smsService->verifyCode($this->phoneNumber, $this->code);

        if (!$result) {
            $this->addError('code', 'Некорректный код. Попробуйте еще раз запросить код.');
        } else {
            $this->redirectRoute('welcome');
        }
    }

    public function getNextTryingSecondsIntervalProperty(): float|int
    {

        return 0;
        if ($this->lastRequestedAt === null) {
            return 0;
        }

        $controlTime = $this->lastRequestedAt->clone()->addMinute();

        if ($controlTime->lessThan(now())) {
            return 0;
        }

        return $controlTime->diffInSeconds(now());
    }
}
