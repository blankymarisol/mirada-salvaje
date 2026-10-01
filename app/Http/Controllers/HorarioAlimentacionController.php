<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorarioAlimentacionRequest;
use App\Models\Dieta;
use App\Models\HorarioAlimentacion;
use App\Support\SimplePdfReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class HorarioAlimentacionController extends Controller
{
    public function index()
    {
        $horarios = HorarioAlimentacion::with(['dieta.animal', 'dieta.alimento'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_programada', 'desc')
            ->paginate(15);

        return view('horarios-alimentacion.index', compact('horarios'));
    }

    public function create()
    {
        $dietas = Dieta::with('animal')->where('activa', true)->get();

        return view('horarios-alimentacion.create', compact('dietas'));
    }

    public function store(StoreHorarioAlimentacionRequest $request)
    {
        HorarioAlimentacion::create($request->validated());

        return redirect()->route('horarios-alimentacion.index')->with('status', 'Horario de alimentación programado.');
    }

    public function show(HorarioAlimentacion $horario)
    {
        $horario->load(['dieta.animal', 'dieta.alimento', 'registradoPor']);

        return view('horarios-alimentacion.show', compact('horario'));
    }

    public function edit(HorarioAlimentacion $horario)
    {
        $dietas = Dieta::with('animal')->where('activa', true)->get();

        return view('horarios-alimentacion.edit', compact('horario', 'dietas'));
    }

    public function update(StoreHorarioAlimentacionRequest $request, HorarioAlimentacion $horario)
    {
        $horario->update($request->validated());

        return redirect()->route('horarios-alimentacion.index')->with('status', 'Horario actualizado.');
    }

    /**
     * Marca la toma como registrada y descuenta stock automáticamente
     * del alimento de su dieta. Este es el requisito clave del sprint:
     * "Registro de consumo descuenta stock automáticamente".
     */
    public function registrarConsumo(Request $request, HorarioAlimentacion $horario)
    {
        if ($horario->registrado) {
            return redirect()->route('horarios-alimentacion.index')
                ->with('status', 'Esa toma ya estaba registrada; no se descontó stock de nuevo.');
        }

        $dieta = $horario->dieta;
        $alimento = $dieta->alimento;

        $cantidad = (float) ($horario->cantidad ?? $dieta->cantidad_racion);
        $alimento->descontarStock($cantidad);

        $horario->update([
            'registrado' => true,
            'registrado_en' => now(),
            'registrado_por' => $request->user()?->id,
        ]);

        $mensaje = "Consumo registrado: se descontaron {$cantidad} {$alimento->unidad_medida} de {$alimento->nombre_alimento}.";

        if ($alimento->refresh()->stock_bajo) {
            $mensaje .= ' ⚠️ El stock de este alimento ya está en el mínimo o por debajo.';
        }

        return redirect()->route('horarios-alimentacion.index')->with('status', $mensaje);
    }

    /**
     * Reporte de consumo: agrupa por alimento el total consumido dentro
     * de un rango de fechas, usando solo las tomas ya marcadas como
     * "registrado" (consumo real, no solo programado).
     */
    public function reporte(Request $request)
    {
        [$fechaInicio, $fechaFin, $reporte] = $this->datosDelReporte($request);

        return view('horarios-alimentacion.reporte', compact('reporte', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Exporta el reporte de consumo de inventario a un PDF descargable.
     */
    public function reportePdf(Request $request): Response
    {
        [$fechaInicio, $fechaFin, $reporte] = $this->datosDelReporte($request);

        $filas = $reporte->map(fn ($fila) => [
            $fila->alimento,
            number_format((float) $fila->total_consumido, 2).' '.$fila->unidad_medida,
            (string) $fila->tomas_registradas,
        ])->all();

        $pdf = SimplePdfReport::make(
            'Reporte de consumo de inventario (alimentación)',
            ["Periodo: {$this->formatearFecha($fechaInicio)} al {$this->formatearFecha($fechaFin)}"],
            ['Alimento', 'Total consumido', 'Tomas registradas'],
            $filas,
            [40, 260, 420]
        );

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reporte-consumo-alimentacion.pdf"',
        ]);
    }

    /**
     * Exporta el reporte de consumo de inventario a un archivo CSV.
     */
    public function reporteCsv(Request $request): Response
    {
        [, , $reporte] = $this->datosDelReporte($request);

        $filas = $reporte->map(fn ($fila) => [
            $fila->alimento,
            number_format((float) $fila->total_consumido, 2, '.', ''),
            $fila->unidad_medida,
            $fila->tomas_registradas,
        ])->all();

        $csv = $this->aCsv(['Alimento', 'Total consumido', 'Unidad', 'Tomas registradas'], $filas);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte-consumo-alimentacion.csv"',
        ]);
    }

    /**
     * @return array{0: string, 1: string, 2: Collection}
     */
    private function datosDelReporte(Request $request): array
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        // Importante: usamos whereDate() (no whereBetween con strings planos)
        // porque la columna "fecha" se guarda con hora incluida (ej.
        // "2026-10-01 00:00:00"); un whereBetween comparando contra
        // "2026-10-01" simple descartaba registros del último día del rango.
        $registros = HorarioAlimentacion::with('dieta.alimento')
            ->where('registrado', true)
            ->whereDate('fecha', '>=', $fechaInicio)
            ->whereDate('fecha', '<=', $fechaFin)
            ->get();

        $reporte = $registros
            ->groupBy(fn ($horario) => $horario->dieta->alimento->id)
            ->map(function ($grupo) {
                $alimento = $grupo->first()->dieta->alimento;

                return (object) [
                    'alimento' => $alimento->nombre_alimento,
                    'unidad_medida' => $alimento->unidad_medida,
                    'total_consumido' => $grupo->sum(fn ($horario) => $horario->cantidad_efectiva),
                    'tomas_registradas' => $grupo->count(),
                ];
            })
            ->sortByDesc('total_consumido')
            ->values();

        return [$fechaInicio, $fechaFin, $reporte];
    }

    private function formatearFecha(string $fecha): string
    {
        return Carbon::parse($fecha)->format('d/m/Y');
    }

    /**
     * @param  string[]  $headers
     * @param  array<int, array<int, mixed>>  $rows
     */
    private function aCsv(array $headers, array $rows): string
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, "\xEF\xBB\xBF");
        fputcsv($stream, $headers);
        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        $contents = stream_get_contents($stream);
        fclose($stream);

        return $contents;
    }

    public function destroy(HorarioAlimentacion $horario)
    {
        $horario->delete();

        return redirect()->route('horarios-alimentacion.index')->with('status', 'Horario eliminado.');
    }
}