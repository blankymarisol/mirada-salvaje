<?php

namespace Database\Seeders;

use App\Models\InventarioAlimento;
use Illuminate\Database\Seeder;

class InventarioAlimentoSeeder extends Seeder
{
    /**
     * "Carne de res" queda a propósito con stock_actual <= stock_minimo
     * para poder demostrar la alerta de stock bajo sin pasos extra.
     */
    public function run(): void
    {
        $alimentos = [
            ['nombre_alimento' => 'Carne de res', 'tipo' => 'carne', 'unidad_medida' => 'kg', 'stock_actual' => 8, 'stock_minimo' => 15],
            ['nombre_alimento' => 'Pescado fresco', 'tipo' => 'carne', 'unidad_medida' => 'kg', 'stock_actual' => 25, 'stock_minimo' => 10],
            ['nombre_alimento' => 'Fruta mixta', 'tipo' => 'fruta', 'unidad_medida' => 'kg', 'stock_actual' => 40, 'stock_minimo' => 12],
            ['nombre_alimento' => 'Concentrado para primates', 'tipo' => 'concentrado', 'unidad_medida' => 'kg', 'stock_actual' => 18, 'stock_minimo' => 8],
            ['nombre_alimento' => 'Heno', 'tipo' => 'heno', 'unidad_medida' => 'kg', 'stock_actual' => 60, 'stock_minimo' => 20],
        ];

        foreach ($alimentos as $alimento) {
            InventarioAlimento::firstOrCreate(['nombre_alimento' => $alimento['nombre_alimento']], $alimento);
        }
    }
}