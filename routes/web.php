<?php

use App\Http\Controllers\AplicacionClinicaController;
use App\Http\Controllers\DietaController;
use App\Http\Controllers\HorarioAlimentacionController;
use App\Http\Controllers\InventarioAlimentoController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Módulo: Gestión de alimentación (Integrante 3)
|--------------------------------------------------------------------------
| Todavía no existe login del panel interno (lo integra quien arme el
| panel general en Sprint 2). Mientras tanto estas rutas quedan abiertas
| para poder demostrar el módulo; la restricción de "solo admin edita
| stock_minimo" ya está aplicada dentro de UpdateInventarioAlimentoRequest.
*/
Route::resource('dietas', DietaController::class);

Route::resource('inventario-alimentos', InventarioAlimentoController::class)
    ->parameters(['inventario-alimentos' => 'alimento']);

Route::resource('horarios-alimentacion', HorarioAlimentacionController::class)
    ->parameters(['horarios-alimentacion' => 'horario']);

Route::patch('horarios-alimentacion/{horario}/registrar-consumo', [HorarioAlimentacionController::class, 'registrarConsumo'])
    ->name('horarios-alimentacion.registrar-consumo');

/*
|--------------------------------------------------------------------------
| Módulo: Control clínico (Integrante 4)
|--------------------------------------------------------------------------
| La información clínica es un dato sensible: solo el veterinario y el
| admin pueden acceder (middleware rol-veterinario). Incluye la alerta
| de vacunas con próxima dosis vencida o próxima (tarea del 17 sep).
*/
Route::middleware('rol-veterinario')->group(function () {
    Route::get('aplicaciones-clinicas/alertas', [AplicacionClinicaController::class, 'alertas'])
        ->name('aplicaciones-clinicas.alertas');

    Route::get('animales/{animal}/historial-clinico', [AplicacionClinicaController::class, 'historial'])
        ->name('aplicaciones-clinicas.historial');

    Route::resource('aplicaciones-clinicas', AplicacionClinicaController::class)
        ->parameters(['aplicaciones-clinicas' => 'aplicacionClinica']);
});

/*
|--------------------------------------------------------------------------
| Ayuda temporal SOLO para desarrollo local
|--------------------------------------------------------------------------
| Como aún no hay pantalla de login, este atajo permite iniciar sesión
| como un usuario con un rol específico para demostrar el módulo.
*/
if (app()->environment('local')) {
    Route::get('/dev-login/{rol}', function (string $rol) {
        $user = User::whereHas('rol', fn ($q) => $q->where('nombre', $rol))->firstOrFail();
        Auth::login($user);

        return redirect('/')->with('status', "Sesión de prueba iniciada como '{$rol}' ({$user->email}).");
    })->name('dev-login');
}
