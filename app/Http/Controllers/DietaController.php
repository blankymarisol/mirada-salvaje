<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDietaRequest;
use App\Models\Animal;
use App\Models\Dieta;
use App\Models\InventarioAlimento;

class DietaController extends Controller
{
    public function index()
    {
        $dietas = Dieta::with(['animal', 'alimento'])->latest()->paginate(10);

        return view('dietas.index', compact('dietas'));
    }

    public function create()
    {
        $animales = Animal::orderBy('nombre')->get();
        $alimentos = InventarioAlimento::orderBy('nombre_alimento')->get();

        return view('dietas.create', compact('animales', 'alimentos'));
    }

    public function store(StoreDietaRequest $request)
    {
        Dieta::create($request->validated());

        return redirect()->route('dietas.index')->with('status', 'Dieta registrada correctamente.');
    }

    public function show(Dieta $dieta)
    {
        $dieta->load(['animal', 'alimento', 'horarios' => fn ($q) => $q->latest('fecha')->latest('hora_programada')]);

        return view('dietas.show', compact('dieta'));
    }

    public function edit(Dieta $dieta)
    {
        $animales = Animal::orderBy('nombre')->get();
        $alimentos = InventarioAlimento::orderBy('nombre_alimento')->get();

        return view('dietas.edit', compact('dieta', 'animales', 'alimentos'));
    }

    public function update(StoreDietaRequest $request, Dieta $dieta)
    {
        $dieta->update($request->validated());

        return redirect()->route('dietas.index')->with('status', 'Dieta actualizada correctamente.');
    }

    public function destroy(Dieta $dieta)
    {
        $dieta->delete();

        return redirect()->route('dietas.index')->with('status', 'Dieta eliminada.');
    }
}