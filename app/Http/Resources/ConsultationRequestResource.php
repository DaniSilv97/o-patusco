<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'client_note' => $this->client_note,
            'animal_name' => $this->animal->name,
            'timeframe' => $this->timeframe->name,
        ];
    }
}