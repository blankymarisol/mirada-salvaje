<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HorarioAlimentacion extends Model
{
    protected $table = 'horarios_alimentacion';

    protected $fillable = [
        'dieta_id',
        'fecha',
        'hora_programada',
        'cantidad',
        'registrado',
        'registrado_en',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cantidad' => 'decimal:2',
            'registrado' => 'boolean',
            'registrado_en' => 'datetime',
        ];
    }

    public function dieta(): BelongsTo
    {
        return $this->belongsTo(Dieta::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function getCantidadEfectivaAttribute(): float
    {
        return (float) ($this->cantidad ?? $this->dieta->cantidad_racion);
    }
}