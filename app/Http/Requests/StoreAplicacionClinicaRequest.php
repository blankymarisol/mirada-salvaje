<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAplicacionClinicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'animal_id' => ['required', 'exists:animales,id'],
            'tipo' => ['required', Rule::in(['medicamento', 'vacuna'])],
            'medicamento_id' => ['nullable', 'required_if:tipo,medicamento', 'exists:medicamentos,id'],
            'vacuna_id' => ['nullable', 'required_if:tipo,vacuna', 'exists:vacunas,id'],
            'fecha_aplicacion' => ['required', 'date'],
            'dosis' => ['nullable', 'string', 'max:100'],
            'observaciones' => ['nullable', 'string'],
            'proxima_dosis' => ['nullable', 'date', 'after_or_equal:fecha_aplicacion'],
        ];
    }

    public function messages(): array
    {
        return [
            'medicamento_id.required_if' => 'Debes seleccionar un medicamento cuando el tipo es medicamento.',
            'vacuna_id.required_if' => 'Debes seleccionar una vacuna cuando el tipo es vacuna.',
            'proxima_dosis.after_or_equal' => 'La próxima dosis no puede ser anterior a la fecha de aplicación.',
        ];
    }
}