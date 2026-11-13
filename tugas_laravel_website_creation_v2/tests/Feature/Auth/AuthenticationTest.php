<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // Customers go back to the shop; only admins are sent to the dashboard
        $response->assertRedirect('/');
    }

    public function test_customer_is_not_sent_to_an_admin_page_after_login(): void
    {
        $customer = User::factory()->create();

        // A guest tried the dashboard earlier, so Laravel remembered it
        $this->get('/dashboard')->assertRedirect('/login');

        $this->post('/login', ['email' => $customer->email, 'password' => 'password'])
            ->assertRedirect('/');
    }

    public function test_customer_goes_back_to_checkout_after_login(): void
    {
        $customer = User::factory()->create();

        $this->get('/checkout')->assertRedirect('/login');

        $this->post('/login', ['email' => $customer->email, 'password' => 'password'])
            ->assertRedirect('/checkout');
    }

    public function test_admin_still_goes_to_the_dashboard_after_login(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect('/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
