<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    /**
     * Test that unauthenticated users are redirected to login
     */
    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test that clients can access the dashboard
     */
    public function test_authenticated_client_can_access_dashboard(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test that unauthenticated clients are redirected
     */
    public function test_receptionist_cannot_access_client_dashboard(): void
    {
        $receptionist = $this->createUserWithRole('Rececionista');

        $response = $this->actingAs($receptionist)->get('/dashboard');

        $response->assertRedirect();
    }
}
