<?php


namespace App\Services\SmsService;


use App\Exceptions\SmsServiceException;
use Illuminate\Support\Facades\Http;

class TelesignSmsSender implements ISmsSender
{
    public function send(string $phoneNumber, string $code): void
    {
        $url = sprintf(
            'https://telesign-telesign-send-sms-verification-code-v1.p.rapidapi.com/sms-verification-code?verifyCode=%s&phoneNumber=%s&appName=%s',
            $code,
            $phoneNumber,
            urlencode(config('app.name'))
        );

        $response =
            Http::withHeaders([
                'x-rapidapi-key' => config('telesign.apiKey'),
                'x-rapidapi-host' => config('telesign.apiHost'),
            ])
                ->post($url);

        $message = $response->json('message');

        if ($message != 'Message in progress') {
            throw new SmsServiceException($phoneNumber, $code, $response->json('message', '-'));
        }
    }
}