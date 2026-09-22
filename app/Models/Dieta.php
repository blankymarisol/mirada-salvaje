<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dieta extends Model
{
    protected $fillable = [
        'animal_id',
        'inventario_alimento_id',
        'nombre',
        'cantidad_racion',
        'frecuencia_diaria',
        'observaciones',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_racion' => 'decimal:2',
            'activa' => 'boolean',
        ];
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function alimento(): BelongsTo
    {
        return $this->belongsTo(InventarioAlimento::class, 'inventario_alimento_id');
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(HorarioAlimentacion::class);
    }
}