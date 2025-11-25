<?php

namespace Tests\Unit\Policies;

use App\Models\Animal;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\User;
use Tests\TestCase;

class ConsultationPolicyTest extends TestCase
{
    /**
     * Test that a receptionist can view any consultation
     */
    public function test_receptionist_can_view_consultation(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');
        $veterinarian = $this->createUserWithRole('Veterinário');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $this->assertTrue($receptionist->can('view', $consultation));
    }

    /**
     * Test that the assigned veterinarian can view the consultation
     */
    public function test_veterinarian_can_view_their_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $this->assertTrue($veterinarian->can('view', $consultation));
    }

    /**
     * Test that the animal owner can view their consultation
     */
    public function test_animal_owner_can_view_their_consultation(): void
    {
        $owner = $this->createUserWithRole('Cliente');
        $veterinarian = $this->createUserWithRole('Veterinário');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $this->assertTrue($owner->can('view', $consultation));
    }

    /**
     * Test that an unrelated user cannot view a consultation
     */
    public function test_unrelated_user_cannot_view_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $owner = $this->createUserWithRole('Cliente');
        $unrelated = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $this->assertFalse($unrelated->can('view', $consultation));
    }

    /**
     * Test that a receptionist can update a consultation
     */
    public function test_receptionist_can_update_consultation(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');
        $veterinarian = $this->createUserWithRole('Veterinário');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $this->assertTrue($receptionist->can('update', $consultation));
    }

    /**
     * Test that the assigned veterinarian can update the consultation
     */
    public function test_veterinarian_can_update_their_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $this->assertTrue($veterinarian->can('update', $consultation));
    }

    /**
     * Test that the animal owner cannot update a consultation
     */
    public function test_animal_owner_cannot_update_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $owner = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $owner->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $this->assertFalse($owner->can('update', $consultation));
    }

    /**
     * Test that only receptionists can create consultations
     */
    public function test_receptionist_can_create_consultation(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');

        $this->assertTrue($receptionist->can('create', Consultation::class));
    }

    /**
     * Test that clients cannot create consultations
     */
    public function test_client_cannot_create_consultation(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $this->assertFalse($client->can('create', Consultation::class));
    }

    /**
     * Test that veterinarians cannot create consultations
     */
    public function test_veterinarian_cannot_create_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');

        $this->assertFalse($veterinarian->can('create', Consultation::class));
    }
}
