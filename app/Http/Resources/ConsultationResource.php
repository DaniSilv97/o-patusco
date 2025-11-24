<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'veterinarian_note' => $this->veterinarian_note,
            'client_note' => $this->consultationRequest->client_note,
            'animal_name' => $this->consultationRequest->animal->name,
            'animal_type' => $this->consultationRequest->animal->animalType->name,
            'veterinarian_name' =>$this->veterinarian->name,
            'state' => $this->state->name,
            'state_id' => $this->state->id,
        ];
    }
}