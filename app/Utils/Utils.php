<?php


namespace App\Utils;


class Utils
{
    public function formatPhoneNumber(string $phoneNumber): string
    {
        return str_replace('+', '', str_replace('-', '', $phoneNumber));
    }
}