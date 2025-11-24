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
            'user.name.required' => 'O nome do utilizador é obrigatório.',
            'user.email.required' => 'O email do utilizador é obrigatório.',
            'user.email.email' => 'O email do utilizador deve ser um endereço de email válido.',
            'animal.name.required' => 'O nome do animal é obrigatório.',
            'animal.birthday.required' => 'A data de nascimento do animal é obrigatória.',
            'animal.animalTypeId.required' => 'O tipo de animal é obrigatório.',
            'date.required' => 'A data da consulta é obrigatória.',
            'date.after_or_equal' => 'A data da consulta deve ser hoje ou no futuro.',
            'timeframe.required' => 'O intervalo de tempo da consulta é obrigatório.',
            'observations.max' => 'As observações não podem exceder 1000 caracteres.',
        ];
    }
}