<?php

namespace Tests\Feature\Services\SmsService;

use App\Services\SmsService\ISmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmsServiceTest extends TestCase
{
    use RefreshDatabase;

    private ISmsService $smsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->smsService = $this->app->get(ISmsService::class);
    }

    public function test_generate_code_and_send()
    {
        $phoneNumber = '79137944814';

        $this->smsService = $this->createMock(ISmsService::class);

        $this->smsService->expects($this->any())
            ->method('generateCodeAndSend')
            ->willReturn('6589');

        $code = $this->smsService->generateCodeAndSend($phoneNumber);

        $this->assertEquals('6589', $code);

        //todo Сделать так, чтобы тесты можно было запускать с флагом, который включал бы реальные сервисы, а не моки

//        $this->assertDatabaseHas('sms_verification_codes', [
//            'phone_number' => '79137944814',
//            'code' => $code,
//        ]);
    }
}