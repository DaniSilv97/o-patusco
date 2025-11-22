<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'animal_type' => $this->animalType->name,
            'birthday' => $this->birthday,
        ];
    }
}