<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VeterinarianUpdateConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state_id' => 'required|uuid|exists:consultation_states,id',
            'veterinarian_note' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'state_id.required' => 'Estado da consulta é obrigatório.',
            'state_id.uuid' => 'Estado inválido.',
            'state_id.exists' => 'O estado selecionado não existe.',
            'veterinarian_note.required' => 'Observações do veterinário são obrigatórias.',
            'veterinarian_note.max' => 'As observações não podem exceder 255 caracteres.',
        ];
    }
}