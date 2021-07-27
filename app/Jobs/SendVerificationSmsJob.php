<?php

namespace App\Jobs;

use App\Services\SmsService\ISmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendVerificationSmsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private string $rawPhoneNumber;
    private ISmsService $smsService;

    public function __construct(string $rawPhoneNumber, ISmsService $smsService)
    {
        $this->rawPhoneNumber = $rawPhoneNumber;
        $this->smsService = $smsService;
    }

    public function handle()
    {
        $phoneNumber = $this->preparePhoneNumber();

        $this->smsService->generateCodeAndSend($phoneNumber);
    }

    private function preparePhoneNumber(): string
    {
        return str_replace('-', '', str_replace('+', '', $this->rawPhoneNumber));
    }
}