<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_page_successful()
    {
        $response = $this->get('/auth/login');

        $response->assertStatus(200);
    }

    public function test_user_login_via_email_successful()
    {
        $user = User::factory()->create();

        $response = $this->post('/auth/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_user_can_not_login_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/auth/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_user_can_not_submit_form_after_exceeding_attempts()
    {
        $user = User::factory()->create();

        $this->get('/auth/login');

        for ($i = 0; $i < 5; $i++) {
            $response = $this->followingRedirects()->post('/auth/login', [
                'email'    => $user->email,
                'password' => 'wrong'
            ]);
        }

        $response->assertSee('Too Many Requests');
    }
}
