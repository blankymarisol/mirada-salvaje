<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre', 'descuento_porcentaje', 'vigente_desde', 'vigente_hasta', 'activa',
    ];

    protected $casts = [
        'vigente_desde' => 'date',
        'vigente_hasta' => 'date',
        'activa' => 'boolean',
    ];

    public function ventas(): HasMany
    {
        return $this->hasMany(VentaEntrada::class);
    }

    /** Solo promociones activas y dentro de su rango de fechas hoy */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query->where('activa', true)
            ->whereDate('vigente_desde', '<=', now())
            ->whereDate('vigente_hasta', '>=', now());
    }

    public function estaVigente(): bool
    {
        return $this->activa
            && now()->toDateString() >= $this->vigente_desde->toDateString()
            && now()->toDateString() <= $this->vigente_hasta->toDateString();
    }
}
