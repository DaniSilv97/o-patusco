<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user.id' => 'nullable|uuid|exists:users,id',
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|max:255',
            'user.birthday' => 'nullable|date',
            'animal.id' => 'nullable|uuid|exists:animals,id',
            'animal.name' => 'required|string|max:255',
            'animal.birthday' => 'required|date',
            'animal.animalTypeId' => 'required|uuid|exists:animal_types,id',
            'date' => 'required|date|after_or_equal:today',
            'timeframe' => 'required|uuid|exists:consultation_timeframes,id',
            'observations' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'user.name.required' => 'The user name is required.',
            'user.email.required' => 'The user email is required.',
            'user.email.email' => 'The user email must be a valid email address.',
            'animal.name.required' => 'The animal name is required.',
            'animal.birthday.required' => 'The animal birthday is required.',
            'animal.animalTypeId.required' => 'The animal type is required.',
            'date.required' => 'The consultation date is required.',
            'date.after_or_equal' => 'The consultation date must be today or in the future.',
            'timeframe.required' => 'The consultation timeframe is required.',
            'observations.max' => 'The observations cannot exceed 1000 characters.',
        ];
    }
}