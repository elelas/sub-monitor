<?php

namespace App\Http\Requests\Auth;

use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class SendSmsVerificationCodeRequest extends FormRequest
{
    public function rules(PhoneNumberRule $phoneNumberRule): array
    {
        return [
            'phone' => ['required', 'string', $phoneNumberRule],
        ];
    }
}