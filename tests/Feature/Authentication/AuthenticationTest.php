<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Test that users can log in with valid credentials
     */
    public function test_users_can_authenticate_with_valid_credentials(): void
    {
        // Create a role first
        $role = Role::firstOrCreate(['name' => 'Cliente']);
        
        // Create a user with explicit type
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign the role to the user
        UserRole::factory()
            ->forUser($user)
            ->forRole($role)
            ->create();

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Should redirect to dashboard (since user has Cliente role)
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test that users cannot log in with invalid email
     */
    public function test_users_cannot_authenticate_with_invalid_email(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    /**
     * Test that users cannot log in with invalid password
     */
    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /**
     * Test that authenticated users can log out
     */
    public function test_users_can_logout(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}