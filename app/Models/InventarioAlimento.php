<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioAlimento extends Model
{
    protected $table = 'inventario_alimentos';

    protected $fillable = [
        'nombre_alimento',
        'tipo',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
    ];

    protected function casts(): array
    {
        return [
            'stock_actual' => 'decimal:2',
            'stock_minimo' => 'decimal:2',
        ];
    }

    public function dietas(): HasMany
    {
        return $this->hasMany(Dieta::class);
    }

    public function getStockBajoAttribute(): bool
    {
        return (float) $this->stock_actual <= (float) $this->stock_minimo;
    }

    public function descontarStock(float $cantidad): void
    {
        $this->stock_actual = max(0, (float) $this->stock_actual - $cantidad);
        $this->save();
    }
}