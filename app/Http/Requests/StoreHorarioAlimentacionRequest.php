<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHorarioAlimentacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Se usa tanto para crear como para editar un horario; en edición los
     * campos son "sometimes" porque puede ser una actualización parcial.
     */
    public function rules(): array
    {
        $creando = $this->isMethod('post');

        return [
            'dieta_id' => [$creando ? 'required' : 'sometimes', 'integer', 'exists:dietas,id'],
            'fecha' => [$creando ? 'required' : 'sometimes', 'date'],
            'hora_programada' => [$creando ? 'required' : 'sometimes', 'date_format:H:i'],
            'cantidad' => ['nullable', 'numeric', 'min:0.01'],
            'registrado' => ['sometimes', 'boolean'],
        ];
    }
}