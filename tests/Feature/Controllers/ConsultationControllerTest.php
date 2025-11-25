<?php

namespace Tests\Feature\Controllers;

use App\Models\Animal;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationTimeframe;
use Tests\TestCase;

class ConsultationControllerTest extends TestCase
{
    /**
     * Test that clients can view consultations
     */
    public function test_client_can_view_consultations(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/consultations');

        $response->assertStatus(200);
    }

    /**
     * Test that unauthenticated users cannot view consultations
     */
    public function test_unauthenticated_users_cannot_view_consultations(): void
    {
        $response = $this->get('/consultations');

        $response->assertRedirect('/login');
    }

    /**
     * Test that clients can view their own consultation
     */
    public function test_client_can_view_their_own_consultation(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $veterinarian = $this->createUserWithRole('Veterinário');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $response = $this->actingAs($client)->get("/consultation/{$consultation->id}");

        $response->assertStatus(200);
    }

    /**
     * Test that clients cannot view other clients' consultations
     */
    public function test_client_cannot_view_other_clients_consultation(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $otherClient = $this->createUserWithRole('Cliente');
        $veterinarian = $this->createUserWithRole('Veterinário');
        $animal = Animal::factory()->create(['user_id' => $otherClient->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        $consultation = Consultation::factory()->create([
            'user_id' => $veterinarian->id,
            'consultation_request_id' => $consultationRequest->id,
        ]);

        $response = $this->actingAs($client)->get("/consultation/{$consultation->id}");

        $response->assertStatus(403);
    }

    /**
     * Test that clients can update their consultation request
     */
    public function test_client_can_update_consultation_request(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $timeframe = ConsultationTimeframe::factory()->create();
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()
            ->create([
                'animal_id' => $animal->id,
                'consultation_timeframe_id' => $timeframe->id,
            ]);

        $response = $this->actingAs($client)->put("/consultation/update/{$consultationRequest->id}", [
            'date' => '2025-12-25',
            'consultation_timeframe_id' => $timeframe->id,
            'client_note' => 'Updated note',
        ]);

        $this->assertDatabaseHas('consultation_requests', [
            'id' => $consultationRequest->id,
            'client_note' => 'Updated note',
        ]);
    }

    /**
     * Test that clients can delete their consultation request
     */
    public function test_client_can_delete_consultation_request(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $response = $this->actingAs($client)->delete("/consultation/delete/{$consultationRequest->id}");

        $this->assertDatabaseMissing('consultation_requests', ['id' => $consultationRequest->id]);
    }
}