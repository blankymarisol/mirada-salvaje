<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicamento extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'presentacion',
        'dosis_referencia',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
        ];
    }

    public function aplicaciones(): HasMany
    {
        return $this->hasMany(AplicacionClinica::class);
    }
}