<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_registration_page_successful()
    {
        $response = $this->get('/auth/registration');

        $response->assertStatus(200);
    }

    public function test_user_register_successful()
    {
        Event::fake();

        $user = User::factory()->make();

        $response = $this->post('/auth/registration', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Event::assertDispatched(Registered::class);

        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseHas('users', [
            'name'  => $user->name,
            'email' => $user->email
        ]);
    }
}
