<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * "animales" es una tabla compartida con el módulo de Control clínico.
     * Se usa firstOrCreate para no duplicar animales si se corre varias veces.
     */
    public function run(): void
    {
        $animales = [
            ['nombre' => 'Simba', 'especie' => 'León africano', 'sexo' => 'macho', 'fecha_nacimiento' => '2019-04-12', 'procedencia' => 'Zoológico La Aurora'],
            ['nombre' => 'Nala', 'especie' => 'León africano', 'sexo' => 'hembra', 'fecha_nacimiento' => '2020-01-08', 'procedencia' => 'Zoológico La Aurora'],
            ['nombre' => 'Kiwi', 'especie' => 'Tucán pico iris', 'sexo' => 'macho', 'fecha_nacimiento' => '2021-06-20', 'procedencia' => 'Rescate local'],
            ['nombre' => 'Manchas', 'especie' => 'Jaguar', 'sexo' => 'hembra', 'fecha_nacimiento' => '2018-11-02', 'procedencia' => 'Reserva natural'],
            ['nombre' => 'Coco', 'especie' => 'Mono araña', 'sexo' => 'macho', 'fecha_nacimiento' => '2022-03-15', 'procedencia' => 'Rescate local'],
        ];

        foreach ($animales as $animal) {
            Animal::firstOrCreate(
                ['nombre' => $animal['nombre'], 'especie' => $animal['especie']],
                $animal + ['estado' => 'activo']
            );
        }
    }
}