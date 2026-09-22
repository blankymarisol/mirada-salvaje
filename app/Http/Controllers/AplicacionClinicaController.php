<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAplicacionClinicaRequest;
use App\Models\Animal;
use App\Models\AplicacionClinica;
use App\Models\Medicamento;
use App\Models\Vacuna;

class AplicacionClinicaController extends Controller
{
    public function index()
    {
        $aplicaciones = AplicacionClinica::with(['animal', 'medicamento', 'vacuna'])
            ->latest('fecha_aplicacion')
            ->paginate(10);

        return view('aplicaciones-clinicas.index', compact('aplicaciones'));
    }

    public function create()
    {
        $animales = Animal::orderBy('nombre')->get();
        $medicamentos = Medicamento::orderBy('nombre')->get();
        $vacunas = Vacuna::orderBy('nombre')->get();

        return view('aplicaciones-clinicas.create', compact('animales', 'medicamentos', 'vacunas'));
    }

    public function store(StoreAplicacionClinicaRequest $request)
    {
        AplicacionClinica::create($request->validated());

        return redirect()->route('aplicaciones-clinicas.index')
            ->with('status', 'Aplicación clínica registrada correctamente.');
    }

    public function show(AplicacionClinica $aplicacionClinica)
    {
        $aplicacionClinica->load(['animal', 'medicamento', 'vacuna']);

        return view('aplicaciones-clinicas.show', compact('aplicacionClinica'));
    }

    public function edit(AplicacionClinica $aplicacionClinica)
    {
        $animales = Animal::orderBy('nombre')->get();
        $medicamentos = Medicamento::orderBy('nombre')->get();
        $vacunas = Vacuna::orderBy('nombre')->get();

        return view('aplicaciones-clinicas.edit', compact('aplicacionClinica', 'animales', 'medicamentos', 'vacunas'));
    }

    public function update(StoreAplicacionClinicaRequest $request, AplicacionClinica $aplicacionClinica)
    {
        $aplicacionClinica->update($request->validated());

        return redirect()->route('aplicaciones-clinicas.index')
            ->with('status', 'Aplicación clínica actualizada correctamente.');
    }

    public function destroy(AplicacionClinica $aplicacionClinica)
    {
        $aplicacionClinica->delete();

        return redirect()->route('aplicaciones-clinicas.index')
            ->with('status', 'Aplicación clínica eliminada.');
    }

    /**
     * Historial cronológico de un animal (tarea del 15 sep).
     */
    public function historial(Animal $animal)
    {
        $aplicaciones = $animal->aplicacionesClinicas()
            ->with(['medicamento', 'vacuna'])
            ->orderByDesc('fecha_aplicacion')
            ->get();

        return view('aplicaciones-clinicas.historial', compact('animal', 'aplicaciones'));
    }

    /**
     * Alerta de vacunas (tarea del 17 sep): aplicaciones de vacuna cuya
     * próxima dosis ya venció o vence en los próximos 30 días.
     */
    public function alertas()
    {
        $alertas = AplicacionClinica::with(['animal', 'vacuna'])
            ->where('tipo', 'vacuna')
            ->whereNotNull('proxima_dosis')
            ->whereDate('proxima_dosis', '<=', now()->addDays(30))
            ->orderBy('proxima_dosis')
            ->get();

        return view('aplicaciones-clinicas.alertas', compact('alertas'));
    }
}