<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacuna extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'dosis_recomendadas',
        'intervalo_dias',
    ];

    protected function casts(): array
    {
        return [
            'dosis_recomendadas' => 'integer',
            'intervalo_dias' => 'integer',
        ];
    }

    public function aplicaciones(): HasMany
    {
        return $this->hasMany(AplicacionClinica::class);
    }
}