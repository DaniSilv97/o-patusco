<?php

namespace Database\Factories;

use App\Models\ConsultationRequest;
use App\Models\ConsultationState;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consultation>
 */
class ConsultationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'date' => fake()->dateTimeBetween('-1 year', '+1 month'),
            'veterinarian_note' => fake()->sentence(),
            'consultation_state_id' => ConsultationState::inRandomOrder()->first()?->id ?? ConsultationState::factory(),
            'consultation_request_id' => ConsultationRequest::inRandomOrder()->first()?->id ?? ConsultationRequest::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }

    /**
     * Set a specific consultation request
     */
    public function forRequest(ConsultationRequest|int $request): static
    {
        return $this->state(fn (array $attributes) => [
            'consultation_request_id' => $request instanceof ConsultationRequest ? $request->id : $request,
        ]);
    }

    /**
     * Set a specific state
     */
    public function withState(ConsultationState|int $state): static
    {
        return $this->state(fn (array $attributes) => [
            'consultation_state_id' => $state instanceof ConsultationState ? $state->id : $state,
        ]);
    }

    /**
     * Set a specific veterinarian
     */
    public function forVeterinarian(User|int $veterinarian): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $veterinarian instanceof User ? $veterinarian->id : $veterinarian,
        ]);
    }
}