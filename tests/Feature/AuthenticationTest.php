<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $adminResponse = $this->get('/admin');
        $adminResponse->assertRedirect('/login');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'officer@nemc.go.tz',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'officer@nemc.go.tz',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'officer@nemc.go.tz',
            'password' => Hash::make('password123'),
        ]);

        $this->post('/login', [
            'email' => 'officer@nemc.go.tz',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_dashboard_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'officer@nemc.go.tz',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $logoutResponse = $this->post('/logout');
        $this->assertGuest();
        $logoutResponse->assertRedirect('/login');
    }
}
