<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTareaLimpiezaRequest;
use App\Http\Requests\UpdateTareaLimpiezaRequest;
use App\Models\Area;
use App\Models\TareaLimpieza;
use App\Models\Turno;
use App\Support\SimplePdfReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
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
        return view('limpieza.reporte', ['reporte' => $this->datosDelReporte()]);
    }

    /**
     * Exporta el reporte de cumplimiento de limpieza a un PDF descargable.
     */
    public function reportePdf(): Response
    {
        $reporte = $this->datosDelReporte();

        $filas = $reporte->map(fn ($fila) => [
            $fila->area->nombre,
            $fila->turno->nombre,
            (string) $fila->total,
            (string) $fila->completadas,
            ($fila->total > 0 ? round($fila->completadas / $fila->total * 100) : 0).'%',
        ])->all();

        $pdf = SimplePdfReport::make(
            'Reporte de cumplimiento de limpieza',
            ['Totales agrupados por área y turno'],
            ['Área', 'Turno', 'Total tareas', 'Completadas', '% Cumplimiento'],
            $filas,
            [40, 160, 280, 380, 480]
        );

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reporte-limpieza.pdf"',
        ]);
    }

    /**
     * Exporta el reporte de cumplimiento de limpieza a un archivo CSV.
     */
    public function reporteCsv(): Response
    {
        $reporte = $this->datosDelReporte();

        $filas = $reporte->map(fn ($fila) => [
            $fila->area->nombre,
            $fila->turno->nombre,
            $fila->total,
            $fila->completadas,
            $fila->total > 0 ? round($fila->completadas / $fila->total * 100) : 0,
        ])->all();

        $csv = $this->aCsv(['Área', 'Turno', 'Total tareas', 'Completadas', '% Cumplimiento'], $filas);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte-limpieza.csv"',
        ]);
    }

    private function datosDelReporte(): Collection
    {
        return TareaLimpieza::query()
            ->selectRaw('area_id, turno_id, COUNT(*) as total, SUM(estado = "completada") as completadas')
            ->groupBy('area_id', 'turno_id')
            ->with(['area', 'turno'])
            ->get();
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
}