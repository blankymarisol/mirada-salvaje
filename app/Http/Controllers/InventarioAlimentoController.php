<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInventarioAlimentoRequest;
use App\Models\InventarioAlimento;
use Illuminate\Http\Request;

class InventarioAlimentoController extends Controller
{
    public function index()
    {
        $alimentos = InventarioAlimento::orderBy('nombre_alimento')->get();
        $alertaStockBajo = $alimentos->filter(fn (InventarioAlimento $a) => $a->stock_bajo);

        return view('inventario-alimentos.index', compact('alimentos', 'alertaStockBajo'));
    }

    public function create()
    {
        return view('inventario-alimentos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_alimento' => ['required', 'string', 'max:100'],
            'tipo' => ['nullable', 'string', 'max:50'],
            'unidad_medida' => ['required', 'string', 'max:20'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
        ]);

        $data['stock_minimo'] = $request->user()?->rol?->nombre === 'admin'
            ? $request->validate(['stock_minimo' => ['required', 'numeric', 'min:0']])['stock_minimo']
            : 0;

        InventarioAlimento::create($data);

        return redirect()->route('inventario-alimentos.index')->with('status', 'Alimento agregado al inventario.');
    }

    public function show(InventarioAlimento $alimento)
    {
        $alimento->load('dietas.animal');

        return view('inventario-alimentos.show', compact('alimento'));
    }

    public function edit(InventarioAlimento $alimento)
    {
        return view('inventario-alimentos.edit', compact('alimento'));
    }

    public function update(UpdateInventarioAlimentoRequest $request, InventarioAlimento $alimento)
    {
        $alimento->update($request->validated());

        return redirect()->route('inventario-alimentos.index')->with('status', 'Inventario actualizado.');
    }

    public function destroy(InventarioAlimento $alimento)
    {
        $alimento->delete();

        return redirect()->route('inventario-alimentos.index')->with('status', 'Alimento eliminado del inventario.');
    }
}