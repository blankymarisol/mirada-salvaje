<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            RolSeeder::class,
        ]);

        // Un usuario de prueba por rol, útil para /dev-login/{rol} en local
        // y para probar la restricción "solo admin edita stock mínimo".
        foreach (['admin', 'veterinario', 'cuidador', 'limpieza', 'recepcion'] as $nombreRol) {
            $rol = Rol::where('nombre', $nombreRol)->first();

            if (! $rol) {
                continue;
            }

            User::firstOrCreate(
                ['email' => "{$nombreRol}@mirada-salvaje.test"],
                [
                    'name' => ucfirst($nombreRol).' de prueba',
                    'password' => bcrypt('password'),
                    'rol_id' => $rol->id,
                ]
            );
        }

        // Módulo de Gestión de alimentación (Integrante 3)
        $this->call([
            AnimalSeeder::class,
            InventarioAlimentoSeeder::class,
            DietaSeeder::class,
            HorarioAlimentacionSeeder::class,
        ]);

        // Módulo de Control clínico (Integrante 4)
        $this->call([
            MedicamentoSeeder::class,
            VacunaSeeder::class,
            AplicacionClinicaSeeder::class,
        ]);

        $this->call(EntradasPromocionesSeeder::class);
    }
}
