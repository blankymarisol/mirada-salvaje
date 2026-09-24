<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Turno;
use Illuminate\Database\Eloquent\Factories\Factory;

class TareaLimpiezaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'area_id' => Area::factory(),
            'turno_id' => Turno::factory(),
            'descripcion' => fake()->sentence(),
            'fecha' => now()->format('Y-m-d'),
            'estado' => 'pendiente',
        ];
    }
}
