@extends('layouts.app')

@section('titulo', 'Alertas de vacunas')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Alertas de vacunas</h1>
        <a href="{{ route('aplicaciones-clinicas.index') }}" class="btn secundario">Volver</a>
    </div>

    <div class="card">
        @if ($alertas->isEmpty())
            <p class="muted">No hay vacunas con próxima dosis dentro de los próximos 30 días.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Vacuna</th>
                        <th>Próxima dosis</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alertas as $alerta)
                        <tr>
                            <td>{{ $alerta->animal->nombre }}</td>
                            <td>{{ $alerta->vacuna->nombre ?? 'Vacuna eliminada' }}</td>
                            <td>{{ $alerta->proxima_dosis->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $alerta->proxima_dosis->isPast() ? 'alerta' : 'pendiente' }}">
                                    {{ $alerta->proxima_dosis->isPast() ? 'Vencida' : 'Próxima' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
