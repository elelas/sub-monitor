<?php


namespace App\Services\SmsService;


use App\Exceptions\InvalidVerificationCodeException;
use App\Models\SmsVerificationCode;
use App\Services\VerificationCodeService\ICodeGenerator;
use Illuminate\Support\Facades\DB;
use Throwable;

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
            $code = $this->codeGenerator->generateCodeForNumber(utils()->formatPhoneNumber($phoneNumber));

            SmsVerificationCode::updateOrCreate([
                'phone_number' => $phoneNumber,
            ], [
                'code' => $code,
            ]);

            // todo Заменить реализацию на нотификацию
            $this->smsSender->send($phoneNumber, $code);

            return $code;
        });
    }

    public function verifyCode(string $phoneNumber, string $code): void
    {
        DB::transaction(function () use ($phoneNumber, $code) {
            $entity = SmsVerificationCode::wherePhoneNumber(utils()->formatPhoneNumber($phoneNumber))->first();

            if (!$entity) {
                throw new InvalidVerificationCodeException();
            }

            if ($entity->code !== $code) {
                throw new InvalidVerificationCodeException();
            }

            $entity->delete();
        });
    }
}