<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AplicacionClinicaAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_veterinario_can_access_clinical_alerts(): void
    {
        $rol = Rol::firstOrCreate(['nombre' => 'veterinario']);
        $user = User::factory()->create([
            'rol_id' => $rol->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('aplicaciones-clinicas.alertas'));

        $response->assertOk();
    }

    public function test_cuidador_is_forbidden_from_clinical_routes(): void
    {
        $rol = Rol::firstOrCreate(['nombre' => 'cuidador']);
        $user = User::factory()->create([
            'rol_id' => $rol->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('aplicaciones-clinicas.index'));

        $response->assertForbidden();
    }
}
