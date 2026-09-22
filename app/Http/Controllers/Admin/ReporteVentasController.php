<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VentaEntrada;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteVentasController extends Controller
{
    /**
     * Reporte de ventas por periodo (cantidad y tipo de entrada).
     * La exportación a PDF/Excel se conecta en el sprint 2; por ahora
     * se muestra en pantalla como pide el itinerario del sprint 1.
     */
    public function index(Request $request): View
    {
        $desde = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        $ventas = VentaEntrada::with(['tipoEntrada', 'promocion'])
            ->whereDate('fecha_venta', '>=', $desde)
            ->whereDate('fecha_venta', '<=', $hasta)
            ->orderByDesc('fecha_venta')
            ->get();

        $totalPeriodo = $ventas->sum('total');
        $cantidadPeriodo = $ventas->sum('cantidad');

        return view('admin.entradas.reportes.ventas', compact(
            'ventas', 'desde', 'hasta', 'totalPeriodo', 'cantidadPeriodo'
        ));
    }
}
