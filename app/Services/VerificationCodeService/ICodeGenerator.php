<?php


namespace App\Services\VerificationCodeService;


interface ICodeGenerator
{
    /**
     * @param string $phoneNumber Номер телефона с международным кодом, без пробелов, тире и скобок
     * @return string Сгенерированный код
     */
    public function generateCodeForNumber(string $phoneNumber): string;
}