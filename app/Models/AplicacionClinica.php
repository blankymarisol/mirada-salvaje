<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AplicacionClinica extends Model
{
    protected $table = 'aplicaciones_clinicas';

    protected $fillable = [
        'animal_id',
        'tipo',
        'medicamento_id',
        'vacuna_id',
        'fecha_aplicacion',
        'dosis',
        'observaciones',
        'proxima_dosis',
    ];

    protected function casts(): array
    {
        return [
            'fecha_aplicacion' => 'date',
            'proxima_dosis' => 'date',
        ];
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function vacuna(): BelongsTo
    {
        return $this->belongsTo(Vacuna::class);
    }

    /**
     * Nombre legible del elemento aplicado (medicamento o vacuna).
     */
    public function getElementoNombreAttribute(): string
    {
        return $this->tipo === 'vacuna'
            ? ($this->vacuna?->nombre ?? 'Vacuna eliminada')
            : ($this->medicamento?->nombre ?? 'Medicamento eliminado');
    }
}