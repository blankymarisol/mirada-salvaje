@extends('layouts.app')

@section('titulo', 'Historial clínico')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Historial clínico · {{ $animal->nombre }}</h1>
        <a href="{{ route('aplicaciones-clinicas.index') }}" class="btn secundario">Volver</a>
    </div>

    <div class="card">
        @if ($aplicaciones->isEmpty())
            <p class="muted">Todavía no hay registros clínicos para este animal.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Elemento</th>
                        <th>Dosis</th>
                        <th>Próxima dosis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aplicaciones as $aplicacion)
                        <tr>
                            <td>{{ $aplicacion->fecha_aplicacion->format('d/m/Y') }}</td>
                            <td>{{ $aplicacion->tipo === 'vacuna' ? 'Vacuna' : 'Medicamento' }}</td>
                            <td>{{ $aplicacion->elemento_nombre }}</td>
                            <td>{{ $aplicacion->dosis ?? '—' }}</td>
                            <td>
                                @if ($aplicacion->proxima_dosis)
                                    <span class="badge {{ $aplicacion->proxima_dosis->isPast() ? 'alerta' : 'pendiente' }}">
                                        {{ $aplicacion->proxima_dosis->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="muted">Sin seguimiento</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
