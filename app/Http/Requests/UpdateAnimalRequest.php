<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'animal_type_id' => $this->input('animal.animalTypeId'),
            'name' => $this->input('animal.name'),
            'birthday' => $this->input('animal.birthday'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'animal_type_id' => 'required|uuid|exists:animal_types,id',
            'birthday' => 'required|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do animal é obrigatório.',
            'name.min' => 'O nome do animal deve ter pelo menos 2 caracteres.',
            'name.max' => 'O nome do animal não pode exceder 255 caracteres.',
            'animal_type_id.required' => 'O tipo de animal é obrigatório.',
            'animal_type_id.uuid' => 'O tipo de animal é inválido.',
            'animal_type_id.exists' => 'O tipo de animal selecionado não existe.',
            'birthday.required' => 'A data de nascimento é obrigatória.',
            'birthday.date' => 'A data de nascimento deve ser uma data válida.',
            'birthday.before_or_equal' => 'A data de nascimento não pode ser no futuro.',
        ];
    }
}