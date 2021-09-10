<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class VerificationEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_page_with_send_button_successful()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/auth/verification-email');

        $response->assertStatus(200);
    }

    public function test_email_verify_successful()
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = $this->getVerificationUrl($user);

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $response->assertRedirect(RouteServiceProvider::HOME . '?verified=1');
    }

    public function test_user_can_not_verify_email_because_link_is_invalid()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = $this->getVerificationUrl($user, true);

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertStatus(400);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /**
     * @param User $user
     * @param bool $invalid
     * @return string
     */
    private function getVerificationUrl(User $user, bool $invalid = false): string
    {
        return URL::temporarySignedRoute(
            'verification-email.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => Hash::make($invalid ? 'wrong' : $user->email)]
        );
    }
}
