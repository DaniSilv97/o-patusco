<?php

namespace Tests\Feature\Controllers;

use App\Models\Animal;
use App\Models\AnimalType;
use Tests\TestCase;

class AnimalControllerTest extends TestCase
{
    /**
     * Test that clients can view the animals index
     */
    public function test_client_can_view_animals_index(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/animals');

        $response->assertStatus(200);
    }

    /**
     * Test that unauthenticated users cannot view animals
     */
    public function test_unauthenticated_users_cannot_view_animals(): void
    {
        $response = $this->get('/animals');

        $response->assertRedirect('/login');
    }

    /**
     * Test that clients can view create animal form
     */
    public function test_client_can_view_create_animal_form(): void
    {
        $client = $this->createUserWithRole('Cliente');

        $response = $this->actingAs($client)->get('/animals/create');

        $response->assertStatus(200);
    }

    /**
     * Test that clients can create an animal
     */
    public function test_client_can_create_animal(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $animalType = AnimalType::factory()->create();

        $response = $this->actingAs($client)->post('/animals/create', [
            'animal' => [
                'name' => 'Fluffy',
                'animalTypeId' => $animalType->id,
                'birthday' => '2020-01-15',
            ]
        ]);

        $this->assertDatabaseHas('animals', [
            'name' => 'Fluffy',
            'animal_type_id' => $animalType->id,
            'user_id' => $client->id,
        ]);
    }

    /**
     * Test that clients can view their own animal
     */
    public function test_client_can_view_their_own_animal(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);

        $response = $this->actingAs($client)->get("/animals/{$animal->id}");

        $response->assertStatus(200);
    }

    /**
     * Test that clients cannot view other clients' animals
     */
    public function test_client_cannot_view_other_clients_animal(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $otherClient = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $otherClient->id]);

        $response = $this->actingAs($client)->get("/animals/{$animal->id}");

        $response->assertStatus(403);
    }

    /**
     * Test that clients can update their own animal
     */
    public function test_client_can_update_their_own_animal(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $animalType = AnimalType::factory()->create();
        $animal = Animal::factory()->create([
            'user_id' => $client->id,
            'name' => 'Fluffy',
            'animal_type_id' => $animalType->id,
        ]);

        $response = $this->actingAs($client)->put("/animals/update/{$animal->id}", [
            'animal' => [
                'name' => 'Whiskers',
                'animalTypeId' => $animalType->id,
                'birthday' => '2020-01-15',
            ]
        ]);

        $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'name' => 'Whiskers',
        ]);
    }

    /**
     * Test that clients cannot update other clients' animals
     */
    public function test_client_cannot_update_other_clients_animal(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $otherClient = $this->createUserWithRole('Cliente');
        $animalType = AnimalType::factory()->create();
        $animal = Animal::factory()->create(['user_id' => $otherClient->id]);

        $response = $this->actingAs($client)->put("/animals/update/{$animal->id}", [
            'animal' => [
                'name' => 'NewName',
                'animalTypeId' => $animalType->id,
                'birthday' => '2020-01-15',
            ]
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that clients can delete their own animal
     */
    public function test_client_can_delete_their_own_animal(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $client->id]);

        $response = $this->actingAs($client)->delete("/animals/{$animal->id}");

        $response->assertRedirect('/animals');
        $this->assertDatabaseMissing('animals', ['id' => $animal->id]);
    }

    /**
     * Test that clients cannot delete other clients' animals
     */
    public function test_client_cannot_delete_other_clients_animal(): void
    {
        $client = $this->createUserWithRole('Cliente');
        $otherClient = $this->createUserWithRole('Cliente');
        $animal = Animal::factory()->create(['user_id' => $otherClient->id]);

        $response = $this->actingAs($client)->delete("/animals/{$animal->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('animals', ['id' => $animal->id]);
    }
}