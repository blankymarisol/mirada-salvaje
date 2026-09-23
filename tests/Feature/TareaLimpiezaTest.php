<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Rol;
use App\Models\TareaLimpieza;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TareaLimpiezaTest extends TestCase
{
    use RefreshDatabase;

    private function usuarioConRol(string $nombre): User
    {
        $rol = Rol::firstOrCreate(['nombre' => $nombre]);

        return User::factory()->create(['rol_id' => $rol->id]);
    }

    public function test_usuario_con_rol_limpieza_puede_crear_tarea(): void
    {
        $user = $this->usuarioConRol('limpieza');
        $area = Area::factory()->create();
        $turno = Turno::factory()->create();

        $response = $this->actingAs($user)->post(route('limpieza.store'), [
            'area_id' => $area->id,
            'turno_id' => $turno->id,
            'descripcion' => 'Limpieza general de jaulas',
            'fecha' => now()->format('Y-m-d'),
            'estado' => 'pendiente',
        ]);

        $response->assertRedirect(route('limpieza.index'));
        $this->assertDatabaseHas('tareas_limpieza', ['descripcion' => 'Limpieza general de jaulas']);
    }

    public function test_usuario_sin_rol_autorizado_no_puede_crear_tarea(): void
    {
        $user = $this->usuarioConRol('recepcion');
        $area = Area::factory()->create();
        $turno = Turno::factory()->create();

        $response = $this->actingAs($user)->post(route('limpieza.store'), [
            'area_id' => $area->id,
            'turno_id' => $turno->id,
            'descripcion' => 'Limpieza general',
            'fecha' => now()->format('Y-m-d'),
            'estado' => 'pendiente',
        ]);

        $response->assertForbidden();
    }

    public function test_area_y_turno_son_obligatorios(): void
    {
        $user = $this->usuarioConRol('limpieza');

        $response = $this->actingAs($user)->post(route('limpieza.store'), [
            'descripcion' => 'Limpieza sin área ni turno',
            'fecha' => now()->format('Y-m-d'),
            'estado' => 'pendiente',
        ]);

        $response->assertSessionHasErrors(['area_id', 'turno_id']);
    }

    public function test_verificar_marca_tarea_como_completada(): void
    {
        $user = $this->usuarioConRol('admin');
        $tarea = TareaLimpieza::factory()->create(['estado' => 'pendiente']);

        $this->actingAs($user)->patch(route('limpieza.verificar', $tarea));

        $tarea->refresh();
        $this->assertTrue($tarea->verificado);
        $this->assertSame('completada', $tarea->estado);
        $this->assertSame($user->id, $tarea->verificado_por);
    }

    public function test_listado_muestra_tareas_existentes(): void
    {
        $user = $this->usuarioConRol('cuidador');
        TareaLimpieza::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('limpieza.index'));

        $response->assertOk();
        $response->assertSee('Tareas de limpieza');
    }
}
