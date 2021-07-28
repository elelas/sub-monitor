<?php

namespace App\Exceptions;

use Exception;

class InvalidVerificationCodeException extends Exception
{
    protected $message = 'Некорректный код верификации';
}