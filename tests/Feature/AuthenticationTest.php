<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_pages_are_available(): void
    {
        $this->get('/')->assertOk()->assertSee('Campus Help Desk');
        $this->get('/login')->assertOk()->assertSee('Sign in');
        $this->get('/register')->assertOk()->assertSee('Create an account');
    }

    public function test_user_can_register_login_and_logout(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Student',
            'email' => 'new.student@example.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'new.student@example.test', 'role' => 'user']);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post('/login', [
            'email' => 'new.student@example.test',
            'password' => 'Password123!',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_dashboard_redirects_to_admin_area(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/dashboard')->assertRedirect(route('admin.dashboard'));
    }
}
