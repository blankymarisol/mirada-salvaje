<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'veterinario', 'cuidador', 'limpieza', 'recepcion'];

        foreach ($roles as $rol) {
            \App\Models\Rol::firstOrCreate(['nombre' => $rol]);
        }
    }
}
