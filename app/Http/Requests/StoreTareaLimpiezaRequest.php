<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTareaLimpiezaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'area_id' => ['required', 'exists:areas,id'],
            'turno_id' => ['required', 'exists:turnos,id'],
            'asignado_a' => ['nullable', 'exists:users,id'],
            'descripcion' => ['required', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'in:pendiente,en_progreso,completada'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'area_id.required' => 'El área es obligatoria.',
            'turno_id.required' => 'El turno es obligatorio.',
        ];
    }
}
