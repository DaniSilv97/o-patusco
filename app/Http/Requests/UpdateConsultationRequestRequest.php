<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultationRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date|after_or_equal:today',
            'consultation_timeframe_id' => 'required|uuid|exists:consultation_timeframes,id',
            'client_note' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'A data da consulta é obrigatória.',
            'date.date' => 'A data da consulta deve ser uma data válida.',
            'date.after_or_equal' => 'A data da consulta deve ser hoje ou no futuro.',
            'consultation_timeframe_id.required' => 'O intervalo de tempo da consulta é obrigatório.',
            'consultation_timeframe_id.uuid' => 'O intervalo de tempo é inválido.',
            'consultation_timeframe_id.exists' => 'O intervalo de tempo selecionado não existe.',
            'client_note.max' => 'A nota do cliente não pode exceder 500 caracteres.',
        ];
    }
}