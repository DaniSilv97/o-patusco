<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\ConsultationTimeframe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsultationRequest>
 */
class ConsultationRequestFactory extends Factory
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
            'date' => fake()->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'client_note' => fake()->sentence(),
            'consultation_timeframe_id' => ConsultationTimeframe::inRandomOrder()->first()?->id ?? ConsultationTimeframe::factory(),
            'animal_id' => Animal::inRandomOrder()->first()?->id ?? Animal::factory(),
        ];
    }

    /**
     * Set a specific animal
     */
    public function forAnimal(Animal|int $animal): static
    {
        return $this->state(fn (array $attributes) => [
            'animal_id' => $animal instanceof Animal ? $animal->id : $animal,
        ]);
    }

    /**
     * Set a specific timeframe
     */
    public function withTimeframe(ConsultationTimeframe|int $consultationTimeframe): static
    {
        return $this->state(fn (array $attributes) => [
            'consultation_timeframe_id' => $consultationTimeframe instanceof ConsultationTimeframe ? $consultationTimeframe->id : $consultationTimeframe,
        ]);
    }
}
