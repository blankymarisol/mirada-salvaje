<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Dieta;
use App\Models\InventarioAlimento;
use Illuminate\Database\Seeder;

class DietaSeeder extends Seeder
{
    public function run(): void
    {
        $simba = Animal::where('nombre', 'Simba')->first();
        $nala = Animal::where('nombre', 'Nala')->first();
        $kiwi = Animal::where('nombre', 'Kiwi')->first();
        $manchas = Animal::where('nombre', 'Manchas')->first();

        $carne = InventarioAlimento::where('nombre_alimento', 'Carne de res')->first();
        $pescado = InventarioAlimento::where('nombre_alimento', 'Pescado fresco')->first();
        $fruta = InventarioAlimento::where('nombre_alimento', 'Fruta mixta')->first();

        if (! $simba || ! $carne) {
            return;
        }

        $dietas = [
            ['animal_id' => $simba->id, 'inventario_alimento_id' => $carne->id, 'nombre' => 'Dieta de mantenimiento - Simba', 'cantidad_racion' => 6, 'frecuencia_diaria' => 2],
            ['animal_id' => $nala->id, 'inventario_alimento_id' => $carne->id, 'nombre' => 'Dieta de mantenimiento - Nala', 'cantidad_racion' => 5, 'frecuencia_diaria' => 2],
            ['animal_id' => $kiwi->id, 'inventario_alimento_id' => $fruta->id, 'nombre' => 'Dieta frugívora - Kiwi', 'cantidad_racion' => 0.5, 'frecuencia_diaria' => 3],
            ['animal_id' => $manchas->id, 'inventario_alimento_id' => $pescado->id, 'nombre' => 'Dieta de mantenimiento - Manchas', 'cantidad_racion' => 4, 'frecuencia_diaria' => 1],
        ];

        foreach ($dietas as $dieta) {
            Dieta::firstOrCreate(
                ['animal_id' => $dieta['animal_id'], 'nombre' => $dieta['nombre']],
                $dieta + ['activa' => true]
            );
        }
    }
}