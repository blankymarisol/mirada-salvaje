<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['nombre' => 'Jaulas', 'descripcion' => 'Recintos de los animales'],
            ['nombre' => 'Sanitarios', 'descripcion' => 'Baños para visitantes y personal'],
            ['nombre' => 'Jardines', 'descripcion' => 'Áreas verdes y senderos'],
            ['nombre' => 'Área de juegos', 'descripcion' => 'Zona infantil'],
            ['nombre' => 'Oficinas', 'descripcion' => 'Administración del zoológico'],
        ];

        foreach ($areas as $area) {
            Area::query()->firstOrCreate(['nombre' => $area['nombre']], $area);
        }
    }
}
