<?php

namespace Tests\Feature\Controllers;

use App\Models\Animal;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationState;
use Tests\TestCase;

class ReceptionistControllerTest extends TestCase
{
    /**
     * Test that receptionists can view the dashboard
     */
    public function test_receptionist_can_view_dashboard(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');

        $response = $this->actingAs($receptionist)->get('/receptionist/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test that non-receptionists cannot view receptionist dashboard
     */
    public function test_non_receptionist_cannot_view_receptionist_dashboard(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/receptionist/dashboard');

        // The middleware redirects instead of returning 403
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test that receptionists can view consultation requests
     */
    public function test_receptionist_can_view_consultation_request(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $response = $this->actingAs($receptionist)->get("/receptionist/consultation/{$consultationRequest->id}");

        $response->assertStatus(200);
    }

    /**
     * Test that receptionists can create consultations
     */
    public function test_receptionist_can_create_consultation(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');
        $veterinarian = $this->createUserWithRole('Veterinário');
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);
        
        // Ensure the 'Atribuída' state exists
        ConsultationState::firstOrCreate(['name' => 'Atribuída']);

        $response = $this->actingAs($receptionist)->post(
            "/receptionist/consultation/create/{$consultationRequest->id}",
            [
                'consultation_date' => '2025-12-25',
                'consultation_time' => '10:00',
                'veterinarian_id' => $veterinarian->id,
            ]
        );

        $this->assertDatabaseHas('consultations', [
            'consultation_request_id' => $consultationRequest->id,
            'user_id' => $veterinarian->id,
        ]);
    }

    /**
     * Test that non-receptionists cannot create consultations
     */
    public function test_non_receptionist_cannot_create_consultation(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $veterinarian = $this->createUserWithRole('Veterinário');
        $otherClient = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $otherClient->id]);
        $consultationRequest = ConsultationRequest::factory()->create(['animal_id' => $animal->id]);

        $response = $this->actingAs($client)->post(
            "/receptionist/consultation/create/{$consultationRequest->id}",
            [
                'consultation_date' => '2025-12-25',
                'consultation_time' => '10:00',
                'veterinarian_id' => $veterinarian->id,
            ]
        );

        // The middleware redirects instead of returning 403
        $response->assertRedirect('/dashboard');
    }
}