<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'animal.name' => 'required|string|min:2|max:255',
            'animal.animalTypeId' => 'required|uuid|exists:animal_types,id',
            'animal.birthday' => 'required|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'animal.name.required' => 'O nome do animal é obrigatório.',
            'animal.name.min' => 'O nome do animal deve ter pelo menos 2 caracteres.',
            'animal.name.max' => 'O nome do animal não pode exceder 255 caracteres.',
            'animal.animalTypeId.required' => 'O tipo de animal é obrigatório.',
            'animal.animalTypeId.uuid' => 'O tipo de animal é inválido.',
            'animal.animalTypeId.exists' => 'O tipo de animal selecionado não existe.',
            'animal.birthday.required' => 'A data de nascimento é obrigatória.',
            'animal.birthday.date' => 'A data de nascimento deve ser uma data válida.',
            'animal.birthday.before_or_equal' => 'A data de nascimento não pode ser no futuro.',
        ];
    }
}