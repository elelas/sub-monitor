<?php


namespace App\Services\SmsService;


interface ISmsService
{
    public function generateCodeAndSend(string $phoneNumber): string;

    public function verifyCode(string $phoneNumber, string $code): bool;
}