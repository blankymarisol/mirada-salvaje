<?php

namespace Database\Seeders;

use App\Models\Turno;
use Illuminate\Database\Seeder;

class TurnoSeeder extends Seeder
{
    public function run(): void
    {
        $turnos = [
            ['nombre' => 'Mañana', 'hora_inicio' => '06:00', 'hora_fin' => '12:00'],
            ['nombre' => 'Tarde', 'hora_inicio' => '12:00', 'hora_fin' => '18:00'],
            ['nombre' => 'Noche', 'hora_inicio' => '18:00', 'hora_fin' => '00:00'],
        ];

        foreach ($turnos as $turno) {
            Turno::query()->firstOrCreate(['nombre' => $turno['nombre']], $turno);
        }
    }
}
