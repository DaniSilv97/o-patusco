<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
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
            'user_id' => User::factory(),
            'role_id' => Role::inRandomOrder()->first()?->id ?? Role::factory(),
        ];
    }

    /**
     * Use an existing user
     */
    public function forUser(User|int $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user instanceof User ? $user->id : $user,
        ]);
    }

    /**
     * Use an existing role
     */
    public function forRole(Role|int $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => $role instanceof Role ? $role->id : $role,
        ]);
    }
}
