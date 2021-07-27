<?php


namespace App\Services\SmsService;


interface ISmsSender
{
    /**
     * @param string $phoneNumber Номер телефона с международным кодом, без пробелов, тире и скобок
     * @param string $code Верификационный код
     */
    public function send(string $phoneNumber, string $code): void;
}