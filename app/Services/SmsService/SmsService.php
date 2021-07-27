<?php


namespace App\Services\SmsService;


use App\Models\SmsVerificationCode;
use App\Services\VerificationCodeService\ICodeGenerator;
use Illuminate\Support\Facades\DB;

class SmsService implements ISmsService
{
    private ICodeGenerator $codeGenerator;
    private ISmsSender $smsSender;

    /**
     * SmsService constructor.
     * @param ICodeGenerator $codeGenerator
     * @param ISmsSender $smsSender
     */
    public function __construct(ICodeGenerator $codeGenerator, ISmsSender $smsSender)
    {
        $this->codeGenerator = $codeGenerator;
        $this->smsSender = $smsSender;
    }

    public function generateCodeAndSend(string $phoneNumber): string
    {
        return DB::transaction(function () use ($phoneNumber) {
            $code = $this->codeGenerator->generateCodeForNumber($this->preparePhoneNumber($phoneNumber));

            SmsVerificationCode::updateOrCreate([
                'phone_number' => $phoneNumber,
            ], [
                'code' => $code,
            ]);

            $this->smsSender->send($phoneNumber, $code);

            return $code;
        });
    }

    public function verifyCode(string $phoneNumber, string $code): bool
    {
        return DB::transaction(function () use ($phoneNumber, $code) {
            $entity = SmsVerificationCode::wherePhoneNumber($this->preparePhoneNumber($phoneNumber))->first();

            if (!$entity) {
                return false;
            }

            if ($entity->code !== $code) {
                return false;
            }

            $entity->delete();

            return true;
        });
    }

    private function preparePhoneNumber(string $phoneNumber): string
    {
        return str_replace('+', '', str_replace('-', '', $phoneNumber));
    }
}