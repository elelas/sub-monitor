<?php


namespace App\Services\VerificationCodeService;


use App\Models\SmsVerificationCode;
use Faker\Factory;

class CodeGenerator implements ICodeGenerator
{
    public function generateCodeForNumber(string $phoneNumber): string
    {
        $faker = Factory::create();

        do {
            $code = $faker->unique()->numberBetween(1111, 9999);
        } while (!$this->isValid($phoneNumber, $code));

        return $code;
    }

    private function isValid(string $phoneNumber, string $code): bool
    {
        $codeModel = SmsVerificationCode::where('code', $code)
            ->orWhere('phone_number', $phoneNumber)
            ->first();

        if (!$codeModel) {
            return true;
        }

        return $codeModel->code != $code;
    }
}