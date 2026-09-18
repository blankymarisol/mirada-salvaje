<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Animal extends Model
{
    protected $table = 'animales';

    protected $fillable = [
        'nombre',
        'especie',
        'sexo',
        'fecha_nacimiento',
        'procedencia',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    public function dietas(): HasMany
    {
        return $this->hasMany(Dieta::class);
    }
}