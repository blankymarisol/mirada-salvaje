<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\AplicacionClinica;
use App\Models\Medicamento;
use App\Models\Vacuna;
use Illuminate\Database\Seeder;

class AplicacionClinicaSeeder extends Seeder
{
    public function run(): void
    {
        $simba = Animal::where('nombre', 'Simba')->first();
        $nala = Animal::where('nombre', 'Nala')->first();
        $manchas = Animal::where('nombre', 'Manchas')->first();

        if (! $simba || ! $nala || ! $manchas) {
            return;
        }

        $amoxicilina = Medicamento::where('nombre', 'Amoxicilina')->first();
        $ivermectina = Medicamento::where('nombre', 'Ivermectina')->first();
        $rabia = Vacuna::where('nombre', 'Rabia')->first();
        $moquillo = Vacuna::where('nombre', 'Moquillo (CDV)')->first();

        $aplicaciones = [
            // Simba: historial clínico de ejemplo
            [
                'animal_id' => $simba->id,
                'tipo' => 'vacuna',
                'vacuna_id' => $rabia?->id,
                'fecha_aplicacion' => '2026-08-10',
                'dosis' => '1 dosis',
                'observaciones' => 'Primera dosis de rabia.',
                'proxima_dosis' => '2027-08-10',
            ],
            [
                'animal_id' => $simba->id,
                'tipo' => 'medicamento',
                'medicamento_id' => $amoxicilina?->id,
                'fecha_aplicacion' => '2026-09-05',
                'dosis' => '10 mg/kg',
                'observaciones' => 'Tratamiento por infección respiratoria.',
                'proxima_dosis' => '2026-09-10',
            ],
            // Nala: vacuna de moquillo
            [
                'animal_id' => $nala->id,
                'tipo' => 'vacuna',
                'vacuna_id' => $moquillo?->id,
                'fecha_aplicacion' => '2026-09-01',
                'dosis' => '1/2 dosis',
                'observaciones' => 'Primera dosis de moquillo.',
                'proxima_dosis' => '2026-10-01',
            ],
            // Manchas: desparasitación
            [
                'animal_id' => $manchas->id,
                'tipo' => 'medicamento',
                'medicamento_id' => $ivermectina?->id,
                'fecha_aplicacion' => '2026-09-12',
                'dosis' => '0.2 mg/kg',
                'observaciones' => 'Desparasitación rutinaria.',
                'proxima_dosis' => null,
            ],
        ];

        foreach ($aplicaciones as $aplicacion) {
            AplicacionClinica::firstOrCreate(
                [
                    'animal_id' => $aplicacion['animal_id'],
                    'fecha_aplicacion' => $aplicacion['fecha_aplicacion'],
                    'tipo' => $aplicacion['tipo'],
                ],
                $aplicacion
            );
        }
    }
}