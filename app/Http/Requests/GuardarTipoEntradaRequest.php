<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarTipoEntradaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // El middleware de rol ya filtra quién llega hasta aquí (admin/recepción)
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'precio' => ['required', 'numeric', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
