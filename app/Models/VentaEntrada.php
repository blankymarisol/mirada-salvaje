<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaEntrada extends Model
{
    use HasFactory;

    protected $table = 'ventas_entradas';

    protected $fillable = [
        'tipo_entrada_id', 'promocion_id', 'cantidad', 'precio_unitario', 'total', 'fecha_venta',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_venta' => 'datetime',
    ];

    public function tipoEntrada(): BelongsTo
    {
        return $this->belongsTo(TipoEntrada::class);
    }

    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class);
    }
}
