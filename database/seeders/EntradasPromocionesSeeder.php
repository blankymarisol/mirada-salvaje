<?php

namespace Database\Seeders;

use App\Models\Promocion;
use App\Models\TipoEntrada;
use Illuminate\Database\Seeder;

class EntradasPromocionesSeeder extends Seeder
{
    public function run(): void
    {
        $general = TipoEntrada::create(['nombre' => 'General', 'precio' => 50.00]);
        TipoEntrada::create(['nombre' => 'Niño', 'precio' => 25.00]);
        TipoEntrada::create(['nombre' => 'Adulto mayor', 'precio' => 30.00]);

        Promocion::create([
            'nombre' => 'Promo fin de semana',
            'descuento_porcentaje' => 15,
            'vigente_desde' => now()->subDay(),
            'vigente_hasta' => now()->addDays(10),
            'activa' => true,
        ]);

        // Promoción vencida a propósito, para probar la validación de "promoción vencida"
        Promocion::create([
            'nombre' => 'Promo expirada (prueba)',
            'descuento_porcentaje' => 20,
            'vigente_desde' => now()->subDays(30),
            'vigente_hasta' => now()->subDays(5),
            'activa' => false,
        ]);
    }
}
