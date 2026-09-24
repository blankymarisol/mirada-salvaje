<?php

namespace Database\Seeders;

use App\Models\Vacuna;
use Illuminate\Database\Seeder;

class VacunaSeeder extends Seeder
{
    public function run(): void
    {
        $vacunas = [
            [
                'nombre' => 'Rabia',
                'descripcion' => 'Vacuna antirrábica de aplicación anual.',
                'dosis_recomendadas' => 1,
                'intervalo_dias' => 365,
            ],
            [
                'nombre' => 'Moquillo (CDV)',
                'descripcion' => 'Protege contra el moquillo canino.',
                'dosis_recomendadas' => 2,
                'intervalo_dias' => 30,
            ],
            [
                'nombre' => 'Parvovirus',
                'descripcion' => 'Protege contra la parvovirosis.',
                'dosis_recomendadas' => 2,
                'intervalo_dias' => 30,
            ],
            [
                'nombre' => 'Leptospirosis',
                'descripcion' => 'Protege contra la leptospirosis.',
                'dosis_recomendadas' => 1,
                'intervalo_dias' => 365,
            ],
        ];

        foreach ($vacunas as $vacuna) {
            Vacuna::firstOrCreate(['nombre' => $vacuna['nombre']], $vacuna);
        }
    }
}