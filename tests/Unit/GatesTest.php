<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Tests\TestCase;

class GatesTest extends TestCase
{
    /**
     * Test that a user with Cliente role can pass the is-client gate
     */
    public function test_user_with_client_role_can_pass_is_client_gate(): void
    {
        $user = $this->createUserWithRole('Cliente');

        $this->assertTrue($user->can('is-client'));
    }

    /**
     * Test that a user without Cliente role cannot pass the is-client gate
     */
    public function test_user_without_client_role_cannot_pass_is_client_gate(): void
    {
        $user = $this->createUserWithRole('Rececionista');

        $this->assertFalse($user->can('is-client'));
    }

    /**
     * Test that a user with Rececionista role can pass the is-receptionist gate
     */
    public function test_user_with_receptionist_role_can_pass_is_receptionist_gate(): void
    {
        $user = $this->createUserWithRole('Rececionista');

        $this->assertTrue($user->can('is-receptionist'));
    }

    /**
     * Test that a user without Rececionista role cannot pass the is-receptionist gate
     */
    public function test_user_without_receptionist_role_cannot_pass_is_receptionist_gate(): void
    {
        $user = $this->createUserWithRole('Cliente');

        $this->assertFalse($user->can('is-receptionist'));
    }

    /**
     * Test that a user with Veterinário role can pass the is-veterinarian gate
     */
    public function test_user_with_veterinarian_role_can_pass_is_veterinarian_gate(): void
    {
        $user = $this->createUserWithRole('Veterinário');

        $this->assertTrue($user->can('is-veterinarian'));
    }

    /**
     * Test that a user without Veterinário role cannot pass the is-veterinarian gate
     */
    public function test_user_without_veterinarian_role_cannot_pass_is_veterinarian_gate(): void
    {
        $user = $this->createUserWithRole('Cliente');

        $this->assertFalse($user->can('is-veterinarian'));
    }

    /**
     * Test that a user can have multiple roles
     */
    public function test_user_can_have_multiple_roles(): void
    {
        $user = $this->createUserWithRole('Cliente');
        $receptionistRole = Role::where('name', 'Rececionista')->first();
        
        UserRole::factory()
            ->forUser($user)
            ->forRole($receptionistRole)
            ->create();

        $this->assertTrue($user->can('is-client'));
        $this->assertTrue($user->can('is-receptionist'));
        $this->assertFalse($user->can('is-veterinarian'));
    }
}