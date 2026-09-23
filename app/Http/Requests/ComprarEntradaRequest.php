<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComprarEntradaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ruta pública: cualquier visitante puede "comprar"
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_entrada_id' => ['required', 'integer', 'exists:tipos_entrada,id'],
            // cantidad mínima 1: evita cantidades negativas o en cero (caso límite pedido en el sprint)
            'cantidad' => ['required', 'integer', 'min:1', 'max:20'],
            'promocion_id' => ['nullable', 'integer', 'exists:promociones,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'tipo_entrada_id.exists' => 'El tipo de entrada seleccionado no existe.',
        ];
    }
}
