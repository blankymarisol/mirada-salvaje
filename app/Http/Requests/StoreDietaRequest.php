<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDietaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'animal_id' => ['required', 'integer', 'exists:animales,id'],
            'inventario_alimento_id' => ['required', 'integer', 'exists:inventario_alimentos,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'cantidad_racion' => ['required', 'numeric', 'min:0.01'],
            'frecuencia_diaria' => ['required', 'integer', 'min:1', 'max:10'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'activa' => ['sometimes', 'boolean'],
        ];
    }
}