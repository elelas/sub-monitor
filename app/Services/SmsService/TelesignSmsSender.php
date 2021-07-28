<?php


namespace App\Services\SmsService;


use App\Exceptions\SendSmsException;
use Illuminate\Support\Facades\Http;

class TelesignSmsSender implements ISmsSender
{
    public function send(string $phoneNumber, string $code): void
    {
        $url = sprintf(
            'https://telesign-telesign-send-sms-verification-code-v1.p.rapidapi.com/sms-verification-code?verifyCode=%s&phoneNumber=%s&appName=%s',
            $code,
            utils()->formatPhoneNumber($phoneNumber),
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
            throw new SendSmsException($phoneNumber, $code, $response->json('message', '-'));
        }
    }
}