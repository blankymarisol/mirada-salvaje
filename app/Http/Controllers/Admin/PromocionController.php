<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarPromocionRequest;
use App\Models\Promocion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PromocionController extends Controller
{
    public function index(): View
    {
        $promociones = Promocion::orderByDesc('vigente_hasta')->paginate(10);

        return view('admin.entradas.promociones.index', compact('promociones'));
    }

    public function create(): View
    {
        return view('admin.entradas.promociones.form', ['promocion' => new Promocion()]);
    }

    public function store(GuardarPromocionRequest $request): RedirectResponse
    {
        Promocion::create($request->validated());

        return redirect()->route('admin.promociones.index')->with('exito', 'Promoción creada.');
    }

    public function edit(Promocion $promocion): View
    {
        return view('admin.entradas.promociones.form', compact('promocion'));
    }

    public function update(GuardarPromocionRequest $request, Promocion $promocion): RedirectResponse
    {
        $promocion->update($request->validated());

        return redirect()->route('admin.promociones.index')->with('exito', 'Promoción actualizada.');
    }

    public function destroy(Promocion $promocion): RedirectResponse
    {
        $promocion->delete();

        return redirect()->route('admin.promociones.index')->with('exito', 'Promoción eliminada.');
    }
}
