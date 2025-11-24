<?php

namespace App\Policies;

use App\Models\ConsultationRequest;
use App\Models\User;

class ConsultationRequestPolicy
{
    /**
     * Determine whether the user can view the consultation request.
     * 
     * Users allowed to view:
     * - Receptionists
     * - Owners of the Animal in the ConsultationRequest
     */
    public function view(User $user, ConsultationRequest $consultationRequest): bool
    {
        if ($user->can('is-receptionist')) {
            return true;
        }

        if ($consultationRequest->animal?->user_id === $user->id) {
            return true;
        }

        return false;
    }
}