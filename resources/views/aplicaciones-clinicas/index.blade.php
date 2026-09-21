@extends('layouts.app')

@section('titulo', 'Aplicaciones clínicas')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Aplicaciones clínicas</h1>
        <a href="{{ route('aplicaciones-clinicas.create') }}" class="btn">+ Nueva aplicación</a>
    </div>

    <div class="card">
        @if ($aplicaciones->isEmpty())
            <p class="muted">No hay aplicaciones clínicas registradas.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Tipo</th>
                        <th>Elemento</th>
                        <th>Fecha</th>
                        <th>Dosis</th>
                        <th>Próxima dosis</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aplicaciones as $aplicacion)
                        <tr>
                            <td>{{ $aplicacion->animal->nombre }}</td>
                            <td>{{ $aplicacion->tipo === 'vacuna' ? 'Vacuna' : 'Medicamento' }}</td>
                            <td>{{ $aplicacion->elemento_nombre }}</td>
                            <td>{{ $aplicacion->fecha_aplicacion->format('d/m/Y') }}</td>
                            <td>{{ $aplicacion->dosis ?? '—' }}</td>
                            <td>
                                @if ($aplicacion->proxima_dosis)
                                    @php
                                        $estadoProxima = $aplicacion->proxima_dosis->isPast() ? 'alerta' : 'pendiente';
                                    @endphp
                                    <span class="badge {{ $estadoProxima }}">
                                        {{ $aplicacion->proxima_dosis->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="muted">Sin seguimiento</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('aplicaciones-clinicas.show', $aplicacion) }}">Ver</a>
                                ·
                                <a href="{{ route('aplicaciones-clinicas.edit', $aplicacion) }}">Editar</a>
                                ·
                                <form class="inline" action="{{ route('aplicaciones-clinicas.destroy', $aplicacion) }}" method="POST" onsubmit="return confirm('¿Eliminar esta aplicación clínica?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#a33c3c;cursor:pointer;padding:0;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $aplicaciones->links() }}</div>
        @endif
    </div>
@endsection
