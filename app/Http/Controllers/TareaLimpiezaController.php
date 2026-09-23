<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTareaLimpiezaRequest;
use App\Http\Requests\UpdateTareaLimpiezaRequest;
use App\Models\Area;
use App\Models\TareaLimpieza;
use App\Models\Turno;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TareaLimpiezaController extends Controller
{
    public function index(Request $request): View
    {
        $tareas = TareaLimpieza::query()
            ->with(['area', 'turno', 'asignado'])
            ->when($request->filled('area_id'), fn ($q) => $q->where('area_id', $request->area_id))
            ->when($request->filled('turno_id'), fn ($q) => $q->where('turno_id', $request->turno_id))
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->latest('fecha')
            ->paginate(15)
            ->withQueryString();

        return view('limpieza.index', [
            'tareas' => $tareas,
            'areas' => Area::orderBy('nombre')->get(),
            'turnos' => Turno::orderBy('nombre')->get(),
        ]);
    }

    public function create(): View
    {
        return view('limpieza.create', [
            'areas' => Area::orderBy('nombre')->get(),
            'turnos' => Turno::orderBy('nombre')->get(),
        ]);
    }

    public function store(StoreTareaLimpiezaRequest $request): RedirectResponse
    {
        TareaLimpieza::create($request->validated());

        return redirect()
            ->route('limpieza.index')
            ->with('status', 'Tarea de limpieza registrada.');
    }

    public function edit(TareaLimpieza $tareaLimpieza): View
    {
        return view('limpieza.edit', [
            'tarea' => $tareaLimpieza,
            'areas' => Area::orderBy('nombre')->get(),
            'turnos' => Turno::orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdateTareaLimpiezaRequest $request, TareaLimpieza $tareaLimpieza): RedirectResponse
    {
        $tareaLimpieza->update($request->validated());

        return redirect()
            ->route('limpieza.index')
            ->with('status', 'Tarea de limpieza actualizada.');
    }

    public function destroy(TareaLimpieza $tareaLimpieza): RedirectResponse
    {
        $tareaLimpieza->delete();

        return redirect()
            ->route('limpieza.index')
            ->with('status', 'Tarea eliminada.');
    }

    public function verificar(Request $request, TareaLimpieza $tareaLimpieza): RedirectResponse
    {
        $tareaLimpieza->forceFill([
            'estado' => 'completada',
            'verificado' => true,
            'verificado_por' => $request->user()->id,
            'verificado_at' => now(),
        ])->save();

        return redirect()
            ->route('limpieza.index')
            ->with('status', 'Tarea verificada como completada.');
    }

    public function reporte(): View
    {
        $reporte = TareaLimpieza::query()
            ->selectRaw('area_id, turno_id, COUNT(*) as total, SUM(estado = "completada") as completadas')
            ->groupBy('area_id', 'turno_id')
            ->with(['area', 'turno'])
            ->get();

        return view('limpieza.reporte', ['reporte' => $reporte]);
    }
}
