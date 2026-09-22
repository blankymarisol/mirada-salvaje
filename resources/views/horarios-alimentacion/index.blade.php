@extends('layouts.app')

@section('titulo', 'Horarios de alimentación')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Horarios de alimentación</h1>
        <a href="{{ route('horarios-alimentacion.create') }}" class="btn">+ Programar horario</a>
    </div>

    <div class="card">
        @if ($horarios->isEmpty())
            <p class="muted">No hay horarios programados todavía.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Animal</th>
                        <th>Dieta</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($horarios as $horario)
                        <tr>
                            <td>{{ $horario->fecha->format('d/m/Y') }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($horario->hora_programada)->format('H:i') }}</td>
                            <td>{{ $horario->dieta->animal->nombre }}</td>
                            <td>{{ $horario->dieta->nombre }}</td>
                            <td>{{ $horario->cantidad_efectiva }} {{ $horario->dieta->alimento->unidad_medida }}</td>
                            <td>
                                <span class="badge {{ $horario->registrado ? 'ok' : 'pendiente' }}">
                                    {{ $horario->registrado ? 'Registrado' : 'Pendiente' }}
                                </span>
                            </td>
                            <td>
                                @if (! $horario->registrado)
                                    <form class="inline" action="{{ route('horarios-alimentacion.registrar-consumo', $horario) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn" style="padding:.3rem .6rem; font-size:.8rem;">
                                            Registrar consumo
                                        </button>
                                    </form>
                                @else
                                    <span class="muted">
                                        {{ $horario->registrado_en?->format('d/m H:i') }}
                                        @if ($horario->registradoPor)
                                            · {{ $horario->registradoPor->name }}
                                        @endif
                                    </span>
                                @endif
                                ·
                                <a href="{{ route('horarios-alimentacion.edit', $horario) }}">Editar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $horarios->links() }}</div>
        @endif
    </div>
@endsection