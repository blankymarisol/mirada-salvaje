<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventarioAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * "stock_minimo" define el umbral de la alerta de stock bajo, así que
     * solo el rol admin puede modificarlo. Si otro rol lo envía, la
     * validación falla con "prohibited".
     */
    public function rules(): array
    {
        return [
            'nombre_alimento' => ['sometimes', 'string', 'max:100'],
            'tipo' => ['sometimes', 'nullable', 'string', 'max:50'],
            'unidad_medida' => ['sometimes', 'string', 'max:20'],
            'stock_actual' => ['sometimes', 'numeric', 'min:0'],
            'stock_minimo' => [
                'sometimes',
                'numeric',
                'min:0',
                Rule::prohibitedIf(fn () => $this->user()?->rol?->nombre !== 'admin'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'stock_minimo.prohibited' => 'Solo un usuario con rol admin puede editar el stock mínimo.',
        ];
    }
}