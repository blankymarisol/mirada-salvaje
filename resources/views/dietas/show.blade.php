@extends('layouts.app')

@section('titulo', $dieta->nombre)

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">{{ $dieta->nombre }}</h1>
        <div>
            <a href="{{ route('dietas.edit', $dieta) }}" class="btn secundario">Editar</a>
            <a href="{{ route('dietas.index') }}" class="btn secundario">Volver</a>
        </div>
    </div>

    <div class="card">
        <p><strong>Animal:</strong> {{ $dieta->animal->nombre }} ({{ $dieta->animal->especie }})</p>
        <p><strong>Alimento:</strong> {{ $dieta->alimento->nombre_alimento }}</p>
        <p><strong>Ración:</strong> {{ $dieta->cantidad_racion }} {{ $dieta->alimento->unidad_medida }}, {{ $dieta->frecuencia_diaria }}x al día</p>
        <p><strong>Estado:</strong> <span class="badge {{ $dieta->activa ? 'ok' : 'pendiente' }}">{{ $dieta->activa ? 'Activa' : 'Inactiva' }}</span></p>
        @if ($dieta->observaciones)
            <p><strong>Observaciones:</strong> {{ $dieta->observaciones }}</p>
        @endif
    </div>

    <div class="card">
        <div class="toolbar">
            <h2 style="margin:0; font-size:1.05rem;">Horarios de esta dieta</h2>
            <a href="{{ route('horarios-alimentacion.create') }}" class="btn secundario">+ Programar horario</a>
        </div>

        @if ($dieta->horarios->isEmpty())
            <p class="muted">Sin horarios programados todavía.</p>
        @else
            <table>
                <thead>
                    <tr><th>Fecha</th><th>Hora</th><th>Cantidad</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @foreach ($dieta->horarios as $horario)
                        <tr>
                            <td>{{ $horario->fecha->format('d/m/Y') }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($horario->hora_programada)->format('H:i') }}</td>
                            <td>{{ $horario->cantidad_efectiva }} {{ $dieta->alimento->unidad_medida }}</td>
                            <td>
                                <span class="badge {{ $horario->registrado ? 'ok' : 'pendiente' }}">
                                    {{ $horario->registrado ? 'Registrado' : 'Pendiente' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection