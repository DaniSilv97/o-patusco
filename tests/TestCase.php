<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Set up test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Seeds roles that are needed for tests
        $this->seedRoles();
    }

    /**
     * Seed the standard roles used in the application
     */
    protected function seedRoles(): void
    {
        \App\Models\Role::firstOrCreate(['name' => 'Cliente']);
        \App\Models\Role::firstOrCreate(['name' => 'Rececionista']);
        \App\Models\Role::firstOrCreate(['name' => 'Veterinário']);
    }

    /**
     * Create a user with a specific role
     */
    protected function createUserWithRole(string $roleName): \App\Models\User
    {
        $user = \App\Models\User::factory()->create();
        $role = \App\Models\Role::where('name', $roleName)->first();
        
        if ($role) {
            \App\Models\UserRole::factory()
                ->forUser($user)
                ->forRole($role)
                ->create();
        }

        return $user;
    }

    /**
     * Create multiple users with specific roles
     */
    protected function createUsersWithRoles(array $roles): array
    {
        return array_map(fn($role) => $this->createUserWithRole($role), $roles);
    }
}