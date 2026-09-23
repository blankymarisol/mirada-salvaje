@extends('layouts.app')

@section('titulo', 'Reporte de limpieza')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Reporte de cumplimiento por área y turno</h1>
        <a href="{{ route('limpieza.index') }}" class="btn secundario">← Volver a tareas</a>
    </div>

    <div class="card">
        @if ($reporte->isEmpty())
            <p class="muted">Sin datos todavía.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Turno</th>
                        <th>Total tareas</th>
                        <th>Completadas</th>
                        <th>% Cumplimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reporte as $fila)
                        <tr>
                            <td>{{ $fila->area->nombre }}</td>
                            <td>{{ $fila->turno->nombre }}</td>
                            <td>{{ $fila->total }}</td>
                            <td>{{ $fila->completadas }}</td>
                            <td>{{ $fila->total > 0 ? round($fila->completadas / $fila->total * 100) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection