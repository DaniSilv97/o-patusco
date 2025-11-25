<?php

namespace Tests\Unit\Policies;

use App\Models\Animal;
use App\Models\User;
use Tests\TestCase;

class AnimalPolicyTest extends TestCase
{
    /**
     * Test that the owner can view their animal
     */
    public function test_owner_can_view_their_animal(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue($owner->can('view', $animal));
    }

    /**
     * Test that a non-owner cannot view an animal
     */
    public function test_non_owner_cannot_view_animal(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $nonOwner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);

        $this->assertFalse($nonOwner->can('view', $animal));
    }

    /**
     * Test that the owner can update their animal
     */
    public function test_owner_can_update_their_animal(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue($owner->can('update', $animal));
    }

    /**
     * Test that a non-owner cannot update an animal
     */
    public function test_non_owner_cannot_update_animal(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $nonOwner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);

        $this->assertFalse($nonOwner->can('update', $animal));
    }

    /**
     * Test that the owner can delete their animal
     */
    public function test_owner_can_delete_their_animal(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue($owner->can('delete', $animal));
    }

    /**
     * Test that a non-owner cannot delete an animal
     */
    public function test_non_owner_cannot_delete_animal(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $nonOwner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);

        $this->assertFalse($nonOwner->can('delete', $animal));
    }
}
