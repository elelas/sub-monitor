<?php


namespace App\Services\SmsService;


use Illuminate\Support\Facades\Log;

class FakeSmsSender implements ISmsSender
{
    /**
     * @inheritDoc
     */
    public function send(string $phoneNumber, string $code): void
    {
        Log::info('Сообщение отправлено', [
            'phoneNumber' => $phoneNumber,
            'code' => $code
        ]);
    }
}