<?php

namespace Database\Seeders;

use App\Models\Medicamento;
use Illuminate\Database\Seeder;

class MedicamentoSeeder extends Seeder
{
    public function run(): void
    {
        $medicamentos = [
            [
                'nombre' => 'Amoxicilina',
                'descripcion' => 'Antibiótico de amplio espectro para infecciones bacterianas.',
                'presentacion' => 'Suspensión oral',
                'dosis_referencia' => '10 mg/kg cada 12 h',
                'stock' => 25,
            ],
            [
                'nombre' => 'Ivermectina',
                'descripcion' => 'Antiparasitario interno y externo.',
                'presentacion' => 'Inyectable',
                'dosis_referencia' => '0.2 mg/kg',
                'stock' => 40,
            ],
            [
                'nombre' => 'Meloxicam',
                'descripcion' => 'Antiinflamatorio y analgésico.',
                'presentacion' => 'Tabletas',
                'dosis_referencia' => '0.1 mg/kg cada 24 h',
                'stock' => 15,
            ],
            [
                'nombre' => 'Metronidazol',
                'descripcion' => 'Antibiótico para infecciones gastrointestinales.',
                'presentacion' => 'Suspensión oral',
                'dosis_referencia' => '25 mg/kg cada 12 h',
                'stock' => 30,
            ],
        ];

        foreach ($medicamentos as $medicamento) {
            Medicamento::firstOrCreate(['nombre' => $medicamento['nombre']], $medicamento);
        }
    }
}