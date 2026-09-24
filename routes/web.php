<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AplicacionClinicaController;
use App\Http\Controllers\DietaController;
use App\Http\Controllers\HorarioAlimentacionController;
use App\Http\Controllers\InventarioAlimentoController;
use App\Http\Controllers\TareaLimpiezaController;
use App\Http\Controllers\Publico\EntradaPublicoController;
use App\Http\Controllers\Admin\TipoEntradaController;
use App\Http\Controllers\Admin\PromocionController;
use App\Http\Controllers\Admin\ReporteVentasController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return view('inicio');
})->name('inicio');

/*
|--------------------------------------------------------------------------
| Módulo: Gestión de alimentación (Integrante 3)
|--------------------------------------------------------------------------
| Ya existe login real: estas rutas ahora requieren sesión iniciada.
| La restricción de "solo admin edita stock_minimo" sigue aplicada dentro
| de UpdateInventarioAlimentoRequest.
*/
Route::middleware('auth')->group(function () {
    Route::resource('dietas', DietaController::class);

    Route::resource('inventario-alimentos', InventarioAlimentoController::class)
        ->parameters(['inventario-alimentos' => 'alimento']);

    Route::resource('horarios-alimentacion', HorarioAlimentacionController::class)
        ->parameters(['horarios-alimentacion' => 'horario']);

    Route::patch('horarios-alimentacion/{horario}/registrar-consumo', [HorarioAlimentacionController::class, 'registrarConsumo'])
        ->name('horarios-alimentacion.registrar-consumo');
});

/*
|--------------------------------------------------------------------------
| Módulo: Control clínico (Integrante 4)
|--------------------------------------------------------------------------
| La información clínica es un dato sensible: solo el veterinario y el
| admin pueden acceder. Se agrega 'auth' para que sin sesión mande al
| login en vez de un 403 directo. La unificación del nombre del
| middleware (rol-veterinario -> rol:veterinario,admin) queda pendiente,
| no es urgente: ya funciona correctamente.
*/
Route::middleware(['auth', 'rol-veterinario'])->group(function () {
    Route::get('aplicaciones-clinicas/alertas', [AplicacionClinicaController::class, 'alertas'])
        ->name('aplicaciones-clinicas.alertas');

    Route::get('animales/{animal}/historial-clinico', [AplicacionClinicaController::class, 'historial'])
        ->name('aplicaciones-clinicas.historial');

    Route::resource('aplicaciones-clinicas', AplicacionClinicaController::class)
        ->parameters(['aplicaciones-clinicas' => 'aplicacionClinica']);
});

/*
|--------------------------------------------------------------------------
| Módulo: Gestión de limpieza (Integrante 2)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/limpieza', [TareaLimpiezaController::class, 'index'])->name('limpieza.index');
    Route::get('/limpieza/reporte', [TareaLimpiezaController::class, 'reporte'])->name('limpieza.reporte');

    Route::middleware('rol:limpieza,admin')->group(function () {
        Route::get('/limpieza/crear', [TareaLimpiezaController::class, 'create'])->name('limpieza.create');
        Route::post('/limpieza', [TareaLimpiezaController::class, 'store'])->name('limpieza.store');
        Route::get('/limpieza/{tareaLimpieza}/editar', [TareaLimpiezaController::class, 'edit'])->name('limpieza.edit');
        Route::put('/limpieza/{tareaLimpieza}', [TareaLimpiezaController::class, 'update'])->name('limpieza.update');
        Route::delete('/limpieza/{tareaLimpieza}', [TareaLimpiezaController::class, 'destroy'])->name('limpieza.destroy');
        Route::patch('/limpieza/{tareaLimpieza}/verificar', [TareaLimpiezaController::class, 'verificar'])->name('limpieza.verificar');
    });
});

/*
|--------------------------------------------------------------
| Módulo: Entradas y promociones (Integrante 5 - Carlos)
|--------------------------------------------------------------
| Parte pública: sin login, cara al visitante.
| Parte administrativa: ahora protegida con auth + rol (admin, recepcion).
| Se cierra aquí el TODO de Carlos, ya que hoy el foco del equipo es el
| documento de análisis.
*/

// --- Parte pública: sin login, cara al visitante ---
Route::get('/entradas', [EntradaPublicoController::class, 'index'])->name('entradas.publico');
Route::post('/entradas/comprar', [EntradaPublicoController::class, 'comprar'])->name('entradas.comprar');

// --- Parte administrativa ---
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'rol:admin,recepcion'])
    ->group(function () {
        Route::resource('tipos-entrada', TipoEntradaController::class)->except(['show']);
        Route::resource('promociones', PromocionController::class)->except(['show']);
        Route::get('reportes/ventas', [ReporteVentasController::class, 'index'])->name('reportes.ventas');
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

//Rutas de Login
Route::get('/login', [LoginController::class, 'mostrarFormulario'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');