@extends('layouts.app')

@section('contenido')
    <h2>Reporte de consumo de inventario</h2>
    <p style="color:#555;">Total consumido por alimento en un rango de fechas. Solo se cuentan las tomas ya registradas como consumidas.</p>

    <form method="GET" action="{{ route('horarios-alimentacion.reporte') }}"
          style="display:flex; gap:12px; align-items:flex-end; margin:16px 0; flex-wrap:wrap;">
        <div>
            <label for="fecha_inicio" style="display:block; font-weight:bold;">Desde</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaInicio }}">
        </div>
        <div>
            <label for="fecha_fin" style="display:block; font-weight:bold;">Hasta</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="{{ $fechaFin }}">
        </div>
        <button type="submit">Filtrar</button>
    </form>

    <p>
        Periodo: <strong>{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</strong>
        al <strong>{{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</strong>
    </p>

    @if ($reporte->isEmpty())
        <p style="padding:12px; background:#f5f5f5; border-radius:6px;">
            No hay consumos registrados en este periodo.
        </p>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#2f6f4e; text-align:left;">
                    <th style="padding:8px; color:#ffffff;">Alimento</th>
                    <th style="padding:8px; color:#ffffff;">Total consumido</th>
                    <th style="padding:8px; color:#ffffff;">Tomas registradas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reporte as $fila)
                    <tr style="border-bottom:1px solid #ddd;">
                        <td style="padding:8px;">{{ $fila->alimento }}</td>
                        <td style="padding:8px;">{{ number_format($fila->total_consumido, 2) }} {{ $fila->unidad_medida }}</td>
                        <td style="padding:8px;">{{ $fila->tomas_registradas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p style="margin-top:20px;">
        <a href="{{ route('horarios-alimentacion.index') }}">← Volver a horarios</a>
    </p>
@endsection

