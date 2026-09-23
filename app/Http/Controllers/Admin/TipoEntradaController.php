<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarTipoEntradaRequest;
use App\Models\TipoEntrada;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TipoEntradaController extends Controller
{
    public function index(): View
    {
        $tiposEntrada = TipoEntrada::orderBy('nombre')->paginate(10);

        return view('admin.entradas.tipos.index', compact('tiposEntrada'));
    }

    public function create(): View
    {
        return view('admin.entradas.tipos.form', ['tipoEntrada' => new TipoEntrada()]);
    }

    public function store(GuardarTipoEntradaRequest $request): RedirectResponse
    {
        TipoEntrada::create($request->validated());

        return redirect()->route('admin.tipos-entrada.index')->with('exito', 'Tipo de entrada creado.');
    }

    public function edit(TipoEntrada $tipoEntrada): View
    {
        return view('admin.entradas.tipos.form', compact('tipoEntrada'));
    }

    public function update(GuardarTipoEntradaRequest $request, TipoEntrada $tipoEntrada): RedirectResponse
    {
        $tipoEntrada->update($request->validated());

        return redirect()->route('admin.tipos-entrada.index')->with('exito', 'Tipo de entrada actualizado.');
    }

    public function destroy(TipoEntrada $tipoEntrada): RedirectResponse
    {
        $tipoEntrada->delete();

        return redirect()->route('admin.tipos-entrada.index')->with('exito', 'Tipo de entrada eliminado.');
    }
}
