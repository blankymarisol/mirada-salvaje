<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprarEntradaRequest;
use App\Models\Promocion;
use App\Models\TipoEntrada;
use App\Models\VentaEntrada;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EntradaPublicoController extends Controller
{
    /**
     * Página pública: lista de tipos de entrada y promociones vigentes.
     * No requiere login (visitante).
     */
    public function index(): View
    {
        $tiposEntrada = TipoEntrada::where('activo', true)->orderBy('nombre')->get();
        $promocionesVigentes = Promocion::vigentes()->get();

        return view('publico.entradas.index', compact('tiposEntrada', 'promocionesVigentes'));
    }

    /**
     * Simula la compra de una entrada: valida datos, calcula total
     * (aplicando descuento si la promoción es válida) y registra la venta.
     * No se procesa ningún pago real.
     */
    public function comprar(ComprarEntradaRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        $tipoEntrada = TipoEntrada::findOrFail($datos['tipo_entrada_id']);

        $promocion = null;
        if (!empty($datos['promocion_id'])) {
            $promocion = Promocion::find($datos['promocion_id']);

            // Caso límite exigido en el sprint: rechazar promoción vencida/inactiva
            if (!$promocion || !$promocion->estaVigente()) {
                return back()
                    ->withInput()
                    ->withErrors(['promocion_id' => 'Esa promoción ya no está vigente.']);
            }
        }

        $precioUnitario = $tipoEntrada->precio;
        $subtotal = $precioUnitario * $datos['cantidad'];
        $descuento = $promocion ? ($subtotal * $promocion->descuento_porcentaje / 100) : 0;
        $total = round($subtotal - $descuento, 2);

        VentaEntrada::create([
            'tipo_entrada_id' => $tipoEntrada->id,
            'promocion_id' => $promocion?->id,
            'cantidad' => $datos['cantidad'],
            'precio_unitario' => $precioUnitario,
            'total' => $total,
            'fecha_venta' => now(),
        ]);

        return redirect()
            ->route('entradas.publico')
            ->with('exito', "Compra simulada registrada: {$datos['cantidad']} x {$tipoEntrada->nombre} = Q" . number_format($total, 2));
    }
}
