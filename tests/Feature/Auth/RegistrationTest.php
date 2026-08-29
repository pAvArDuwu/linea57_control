<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'apellido' => 'Prueba',
            'ci' => '12345678',
            'telefono' => '76543210',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));
    }

    public function test_unverified_users_are_redirected_to_verification_notice_after_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('verification.notice', absolute: false));
    }

    public function test_users_without_assigned_role_are_redirected_to_pending_screen_after_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'pending@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'pending@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('pending.role', absolute: false));
    }

    public function test_users_cannot_register_with_duplicate_ci(): void
    {
        \App\Models\User::factory()->create([
            'ci' => '9981314',
            'email' => 'original@example.com',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Otro Usuario',
            'apellido' => 'Prueba',
            'ci' => '9981314',
            'telefono' => '76543210',
            'email' => 'nuevo@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['ci']);
        $this->assertDatabaseCount('users', 1);
    }
}
