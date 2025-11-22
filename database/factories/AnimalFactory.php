<?php

namespace Database\Factories;

use App\Models\AnimalType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
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
            'name' => fake()->firstName(),
            'birthday' => fake()->dateTimeBetween('-15 years', '-1 month'),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'animal_type_id' => AnimalType::inRandomOrder()->first()?->id ?? AnimalType::factory(),
        ];
    }

    /**
     * Set a specific owner for the animal
     */
    public function forOwner(User|int $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user instanceof User ? $user->id : $user,
        ]);
    }

    /**
     * Set a specific animal type
     */
    public function ofType(AnimalType|int $type): static
    {
        return $this->state(fn (array $attributes) => [
            'animal_type_id' => $type instanceof AnimalType ? $type->id : $type,
        ]);
    }

    /**
     * Define an animal with a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }
}
