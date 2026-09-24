<?php

namespace Database\Seeders;

use App\Models\Dieta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HorarioAlimentacionSeeder extends Seeder
{
    /**
     * Deja una toma de hoy ya registrada (para ver el historial) y una
     * pendiente (para poder demostrar "registrar consumo" en vivo).
     */
    public function run(): void
    {
        $dietas = Dieta::all();

        foreach ($dietas as $dieta) {
            $dieta->horarios()->firstOrCreate(
                ['fecha' => Carbon::today(), 'hora_programada' => '07:00'],
                ['registrado' => true, 'registrado_en' => Carbon::today()->setTime(7, 5)]
            );

            $dieta->horarios()->firstOrCreate(
                ['fecha' => Carbon::today(), 'hora_programada' => '15:00'],
                ['registrado' => false]
            );
        }
    }
}