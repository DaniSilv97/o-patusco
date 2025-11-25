<?php

namespace Tests\Unit\Models;

use App\Models\AnimalType;
use App\Models\ConsultationRequest;
use App\Models\ConsultationState;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    /**
     * Test that a user can be created
     */
    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test that a user can have roles
     */
    public function test_user_can_have_roles(): void
    {
        $user = User::factory()->create();
        $clientRole = Role::firstOrCreate(['name' => 'Cliente']);
        
        UserRole::factory()
            ->forUser($user)
            ->forRole($clientRole)
            ->create();

        $this->assertTrue($user->roles()->where('name', 'Cliente')->exists());
    }

    /**
     * Test hasRole method returns true when user has the role
     */
    public function test_has_role_returns_true_when_user_has_role(): void
    {
        $user = $this->createUserWithRole('Cliente');

        $this->assertTrue($user->hasRole('Cliente'));
    }

    /**
     * Test hasRole method returns false when user doesn't have the role
     */
    public function test_has_role_returns_false_when_user_does_not_have_role(): void
    {
        $user = $this->createUserWithRole('Cliente');

        $this->assertFalse($user->hasRole('Rececionista'));
    }

    /**
     * Test that a user can have multiple roles
     */
    public function test_user_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();
        $clientRole = Role::firstOrCreate(['name' => 'Cliente']);
        $receptionistRole = Role::firstOrCreate(['name' => 'Rececionista']);
        
        UserRole::factory()->forUser($user)->forRole($clientRole)->create();
        UserRole::factory()->forUser($user)->forRole($receptionistRole)->create();

        $this->assertCount(2, $user->roles);
        $this->assertTrue($user->hasRole('Cliente'));
        $this->assertTrue($user->hasRole('Rececionista'));
    }

    /**
     * Test that a user has a one-to-many relationship with animals
     */
    public function test_user_has_many_animals(): void
    {
        $user = User::factory()->create();
        
        // Create an animal type first (required foreign key)
        $animalType = AnimalType::factory()->create();
        
        // Use the factory to create animals properly with all required fields
        $user->animals()->saveMany([
            \App\Models\Animal::factory()->make(['animal_type_id' => $animalType->id]),
            \App\Models\Animal::factory()->make(['animal_type_id' => $animalType->id]),
        ]);

        $this->assertCount(2, $user->animals);
    }

    /**
     * Test that a user has a one-to-many relationship with consultations
     */
    public function test_user_has_many_consultations(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        
        // Create required dependencies
        $consultationState = ConsultationState::factory()->create();
        $consultationRequest = ConsultationRequest::factory()->create();
        
        // Create consultations with all required fields
        $veterinarian->consultations()->saveMany([
            \App\Models\Consultation::factory()->make([
                'consultation_state_id' => $consultationState->id,
                'consultation_request_id' => $consultationRequest->id,
            ]),
            \App\Models\Consultation::factory()->make([
                'consultation_state_id' => $consultationState->id,
                'consultation_request_id' => $consultationRequest->id,
            ]),
        ]);

        $this->assertCount(2, $veterinarian->consultations);
    }
}