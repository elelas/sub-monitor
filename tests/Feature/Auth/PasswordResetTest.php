<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_page_with_form_successful()
    {
        $response = $this->get('/auth/reset-password');

        $response->assertStatus(200);
    }

    public function test_user_request_new_reset_link_successful()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/auth/reset-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_show_page_with_new_password_form()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/auth/reset-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $response = $this->get(sprintf('/auth/reset-password/new-password?token=%s&email=%s', $notification->token, $user->email));

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_user_save_new_password_successful()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/auth/reset-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $response = $this->post('/auth/reset-password/new-password', [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'newPassword',
                'password_confirmation' => 'newPassword',
            ]);

            $response->assertSessionHasNoErrors();

            $response = $this->post('/auth/login', [
                'email'    => $user->email,
                'password' => 'newPassword'
            ]);

            $response->assertSessionHasNoErrors();

            $response->assertRedirect(RouteServiceProvider::HOME);

            return true;
        });
    }
}
