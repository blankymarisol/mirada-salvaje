@extends('layouts.app')

@section('titulo', 'Detalle de horario')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Horario del {{ $horario->fecha->format('d/m/Y') }}</h1>
        <a href="{{ route('horarios-alimentacion.index') }}" class="btn secundario">Volver</a>
    </div>

    <div class="card">
        <p><strong>Animal:</strong> {{ $horario->dieta->animal->nombre }}</p>
        <p><strong>Dieta:</strong> {{ $horario->dieta->nombre }}</p>
        <p><strong>Hora programada:</strong> {{ \Illuminate\Support\Carbon::parse($horario->hora_programada)->format('H:i') }}</p>
        <p><strong>Cantidad:</strong> {{ $horario->cantidad_efectiva }} {{ $horario->dieta->alimento->unidad_medida }}</p>
        <p><strong>Estado:</strong>
            <span class="badge {{ $horario->registrado ? 'ok' : 'pendiente' }}">
                {{ $horario->registrado ? 'Registrado' : 'Pendiente' }}
            </span>
        </p>
        @if ($horario->registrado)
            <p class="muted">
                Registrado el {{ $horario->registrado_en?->format('d/m/Y H:i') }}
                @if ($horario->registradoPor)
                    por {{ $horario->registradoPor->name }}
                @endif
            </p>
        @else
            <form action="{{ route('horarios-alimentacion.registrar-consumo', $horario) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn">Registrar consumo (descuenta stock)</button>
            </form>
        @endif
    </div>
@endsection