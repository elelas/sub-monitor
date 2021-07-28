<?php


namespace App\Services\SmsService;


use App\Exceptions\InvalidVerificationCodeException;

interface ISmsService
{
    public function generateCodeAndSend(string $phoneNumber): string;

    /**
     * @throws InvalidVerificationCodeException
     */
    public function verifyCode(string $phoneNumber, string $code): void;
}