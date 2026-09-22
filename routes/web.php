<?php

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