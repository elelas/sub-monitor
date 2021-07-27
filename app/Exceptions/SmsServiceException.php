<?php

namespace App\Exceptions;

use Exception;

class SmsServiceException extends Exception
{
    public function __construct(string $phoneNumber, string $code, string $serviceResponseMessage)
    {
        $exceptionMessage = sprintf(
            'Ошибка "%s" при отправке кода %s на номер %s',
            $serviceResponseMessage,
            $code,
            $phoneNumber
        );

        parent::__construct($exceptionMessage);
    }
}