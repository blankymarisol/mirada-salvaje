<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VentaEntrada;
use App\Support\SimplePdfReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReporteVentasController extends Controller
{
    /**
     * Reporte de ventas por periodo (cantidad y tipo de entrada), mostrado
     * en pantalla con opción de exportar a PDF o CSV (ver pdf()/csv()).
     */
    public function index(Request $request): View
    {
        [$desde, $hasta, $ventas, $totalPeriodo, $cantidadPeriodo] = $this->datosDelPeriodo($request);

        return view('admin.entradas.reportes.ventas', compact(
            'ventas', 'desde', 'hasta', 'totalPeriodo', 'cantidadPeriodo'
        ));
    }

    /**
     * Exporta el mismo reporte de ventas a un PDF descargable.
     */
    public function pdf(Request $request): Response
    {
        [$desde, $hasta, $ventas, $totalPeriodo, $cantidadPeriodo] = $this->datosDelPeriodo($request);

        $filas = $ventas->map(fn (VentaEntrada $venta) => [
            $venta->fecha_venta->format('d/m/Y H:i'),
            $venta->tipoEntrada->nombre,
            (string) $venta->cantidad,
            $venta->promocion?->nombre ?? '—',
            'Q'.number_format((float) $venta->total, 2),
        ])->all();

        $pdf = SimplePdfReport::make(
            'Reporte de ventas de entradas',
            ["Periodo: {$this->formatearFecha($desde)} al {$this->formatearFecha($hasta)}"],
            ['Fecha', 'Tipo de entrada', 'Cantidad', 'Promoción', 'Total'],
            $filas,
            [40, 130, 300, 370, 500],
            [
                'Total del periodo: Q'.number_format((float) $totalPeriodo, 2),
                "Entradas vendidas: {$cantidadPeriodo}",
            ]
        );

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reporte-ventas.pdf"',
        ]);
    }

    /**
     * Exporta el mismo reporte de ventas a un archivo CSV (se abre en Excel).
     */
    public function csv(Request $request): Response
    {
        [$desde, $hasta, $ventas] = $this->datosDelPeriodo($request);

        $filas = $ventas->map(fn (VentaEntrada $venta) => [
            $venta->fecha_venta->format('d/m/Y H:i'),
            $venta->tipoEntrada->nombre,
            $venta->cantidad,
            $venta->promocion?->nombre ?? '',
            number_format((float) $venta->total, 2, '.', ''),
        ])->all();

        $csv = $this->aCsv(['Fecha', 'Tipo de entrada', 'Cantidad', 'Promoción', 'Total'], $filas);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte-ventas.csv"',
        ]);
    }

    /**
     * @return array{0: string, 1: string, 2: Collection<int, VentaEntrada>, 3: float|int, 4: int}
     */
    private function datosDelPeriodo(Request $request): array
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

        return [$desde, $hasta, $ventas, $totalPeriodo, $cantidadPeriodo];
    }

    private function formatearFecha(string $fecha): string
    {
        return Carbon::parse($fecha)->format('d/m/Y');
    }

    /**
     * Construye un CSV (con BOM UTF-8, para que Excel reconozca los acentos)
     * a partir de encabezados y filas.
     *
     * @param  string[]  $headers
     * @param  array<int, array<int, mixed>>  $rows
     */
    private function aCsv(array $headers, array $rows): string
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, "\xEF\xBB\xBF"); // BOM UTF-8
        fputcsv($stream, $headers);
        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        $contents = stream_get_contents($stream);
        fclose($stream);

        return $contents;
    }
}