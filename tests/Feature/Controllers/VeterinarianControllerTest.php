<?php

namespace Tests\Feature\Controllers;

use App\Models\Animal;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationState;
use Tests\TestCase;

class VeterinarianControllerTest extends TestCase
{
    /**
     * Test that veterinarians can view the dashboard
     */
    public function test_veterinarian_can_view_dashboard(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');

        $response = $this->actingAs($veterinarian)->get('/veterinarian/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test that non-veterinarians cannot view veterinarian dashboard
     */
    public function test_non_veterinarian_cannot_view_veterinarian_dashboard(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/veterinarian/dashboard');

        // The middleware redirects instead of returning 403
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test that veterinarians can view their assigned consultation
     */
    public function test_veterinarian_can_view_their_assigned_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $response = $this->actingAs($veterinarian)->get("/veterinarian/consultation/{$consultation->id}");

        $response->assertStatus(200);
    }

    /**
     * Test that veterinarians cannot view unassigned consultations
     */
    public function test_veterinarian_cannot_view_unassigned_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $otherVeterinarian = $this->createUserWithRole('Veterinário');
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $otherVeterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $response = $this->actingAs($veterinarian)->get("/veterinarian/consultation/{$consultation->id}");

        $response->assertStatus(403);
    }

    /**
     * Test that veterinarians can update their assigned consultation
     */
    public function test_veterinarian_can_update_their_assigned_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $state = ConsultationState::factory()->create();
        
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
            'consultation_state_id' => $state->id,
            'veterinarian_note' => 'Initial note',
        ]);

        $response = $this->actingAs($veterinarian)->put("/veterinarian/consultation/update/{$consultation->id}", [
            'state_id' => $state->id,
            'veterinarian_note' => 'Updated note',
        ]);

        $this->assertDatabaseHas('consultations', [
            'id' => $consultation->id,
            'veterinarian_note' => 'Updated note',
        ]);
    }

    /**
     * Test that veterinarians cannot update unassigned consultations
     */
    public function test_veterinarian_cannot_update_unassigned_consultation(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');
        $otherVeterinarian = $this->createUserWithRole('Veterinário');
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $state = ConsultationState::factory()->create();
        
        $consultation = Consultation::factory()->create([
            'user_id' => $otherVeterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
            'consultation_state_id' => $state->id,
        ]);

        $response = $this->actingAs($veterinarian)->put(
            "/veterinarian/consultation/update/{$consultation->id}",
            [
                'state_id' => $state->id,
                'veterinarian_note' => 'Updated note',
            ]
        );

        $response->assertStatus(403);
    }
}