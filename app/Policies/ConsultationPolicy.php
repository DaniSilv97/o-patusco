<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConsultationPolicy
{
    /**
     * Determine if the user can view a consultation.
     * 
     * Users allowed to view:
     * - Veterinarians assigned to the Consultation
     * - Owners of the Animal in the ConsultationRequest associated to the Consultation
     * - Receptionists
     */
    public function view(User $user, Consultation $consultation): bool
    {
        if ($user->can('is-receptionist')) {
            return true;
        }

        if ($consultation->user_id === $user->id) {
            return true;
        }

        if ($consultation->consultationRequest?->animal?->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
 