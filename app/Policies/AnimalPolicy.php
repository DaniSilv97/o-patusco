<?php

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnimalPolicy
{
    /**
     * Determine if the user can view an animal.
     * 
     * Users allowed to view:
     * - Owners of the Animal
     */
    public function view(User $user, Animal $animal)
    {
        return $user->id === $animal->user_id;
    }
}
