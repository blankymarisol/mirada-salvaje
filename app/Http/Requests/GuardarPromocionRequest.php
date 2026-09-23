<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarPromocionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'descuento_porcentaje' => ['required', 'integer', 'min:1', 'max:100'],
            'vigente_desde' => ['required', 'date'],
            'vigente_hasta' => ['required', 'date', 'after_or_equal:vigente_desde'],
            'activa' => ['sometimes', 'boolean'],
        ];
    }
}
