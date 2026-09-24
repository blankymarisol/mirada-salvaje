<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TurnoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->word(),
            'hora_inicio' => '06:00',
            'hora_fin' => '12:00',
        ];
    }
}
