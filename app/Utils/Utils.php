<?php

namespace App\Utils;

class Utils
{
    public static function formatPhoneNumber(string $phoneNumber): string
    {
        return str_replace('+', '', str_replace('-', '', $phoneNumber));
    }
}