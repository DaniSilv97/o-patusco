<?php

namespace Tests\Feature\Authorization;

use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    /**
     * Test that clients are redirected to client dashboard
     */
    public function test_client_is_redirected_to_client_dashboard(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/');

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test that receptionists are redirected to receptionist dashboard
     */
    public function test_receptionist_is_redirected_to_receptionist_dashboard(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');

        $response = $this->actingAs($receptionist)->get('/');

        $response->assertRedirect('/receptionist/dashboard');
    }

    /**
     * Test that veterinarians are redirected to veterinarian dashboard
     */
    public function test_veterinarian_is_redirected_to_veterinarian_dashboard(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');

        $response = $this->actingAs($veterinarian)->get('/');

        $response->assertRedirect('/veterinarian/dashboard');
    }

    /**
     * Test that unauthenticated users see the welcome page
     */
    public function test_unauthenticated_users_see_welcome_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that clients cannot access receptionist routes (redirected)
     */
    public function test_client_cannot_access_receptionist_routes(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/receptionist/dashboard');

        // Client is redirected to their dashboard when accessing receptionist routes
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test that clients cannot access veterinarian routes (redirected)
     */
    public function test_client_cannot_access_veterinarian_routes(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/veterinarian/dashboard');

        // Client is redirected to their dashboard when accessing veterinarian routes
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test that receptionists cannot access client routes (redirected)
     */
    public function test_receptionist_cannot_access_client_routes(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');

        $response = $this->actingAs($receptionist)->get('/dashboard');

        // Receptionist is redirected to their dashboard when accessing client routes
        $response->assertRedirect('/receptionist/dashboard');
    }

    /**
     * Test that veterinarians cannot access client routes (redirected)
     */
    public function test_veterinarian_cannot_access_client_routes(): void
    {
        $veterinarian = $this->createUserWithRole('Veterinário');

        $response = $this->actingAs($veterinarian)->get('/dashboard');

        // Veterinarian is redirected to their dashboard when accessing client routes
        $response->assertRedirect('/veterinarian/dashboard');
    }
}