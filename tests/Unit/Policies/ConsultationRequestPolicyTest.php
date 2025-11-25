<?php

namespace Tests\Unit\Policies;

use App\Models\Animal;
use App\Models\ConsultationRequest;
use App\Models\User;
use Tests\TestCase;

class ConsultationRequestPolicyTest extends TestCase
{
    /**
     * Test that a receptionist can view any consultation request
     */
    public function test_receptionist_can_view_consultation_request(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $this->assertTrue($receptionist->can('view', $consultationRequest));
    }

    /**
     * Test that the animal owner can view their consultation request
     */
    public function test_animal_owner_can_view_their_consultation_request(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $this->assertTrue($owner->can('view', $consultationRequest));
    }

    /**
     * Test that a non-owner client cannot view a consultation request
     */
    public function test_non_owner_client_cannot_view_consultation_request(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $nonOwner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $this->assertFalse($nonOwner->can('view', $consultationRequest));
    }

    /**
     * Test that a receptionist can update any consultation request
     */
    public function test_receptionist_can_update_consultation_request(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $this->assertTrue($receptionist->can('update', $consultationRequest));
    }

    /**
     * Test that the animal owner can update their consultation request
     */
    public function test_animal_owner_can_update_their_consultation_request(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $this->assertTrue($owner->can('update', $consultationRequest));
    }

    /**
     * Test that a receptionist can delete any consultation request
     */
    public function test_receptionist_can_delete_consultation_request(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $this->assertTrue($receptionist->can('delete', $consultationRequest));
    }

    /**
     * Test that the animal owner can delete their consultation request
     */
    public function test_animal_owner_can_delete_their_consultation_request(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $this->assertTrue($owner->can('delete', $consultationRequest));
    }
}
