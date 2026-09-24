@extends('layouts.app')

@section('titulo', 'Detalle de aplicación clínica')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Detalle de aplicación clínica</h1>
        <div>
            <a href="{{ route('aplicaciones-clinicas.edit', $aplicacionClinica) }}" class="btn secundario">Editar</a>
            <a href="{{ route('aplicaciones-clinicas.index') }}" class="btn secundario">Volver</a>
        </div>
    </div>

    <div class="card">
        <p><strong>Animal:</strong> {{ $aplicacionClinica->animal->nombre }}</p>
        <p><strong>Tipo:</strong> {{ $aplicacionClinica->tipo === 'vacuna' ? 'Vacuna' : 'Medicamento' }}</p>
        <p><strong>Elemento:</strong> {{ $aplicacionClinica->elemento_nombre }}</p>
        <p><strong>Fecha de aplicación:</strong> {{ $aplicacionClinica->fecha_aplicacion->format('d/m/Y') }}</p>
        <p><strong>Dosis:</strong> {{ $aplicacionClinica->dosis ?? '—' }}</p>
        <p><strong>Próxima dosis:</strong>
            @if ($aplicacionClinica->proxima_dosis)
                <span class="badge {{ $aplicacionClinica->proxima_dosis->isPast() ? 'alerta' : 'pendiente' }}">
                    {{ $aplicacionClinica->proxima_dosis->format('d/m/Y') }}
                </span>
            @else
                <span class="muted">Sin seguimiento</span>
            @endif
        </p>
        @if ($aplicacionClinica->observaciones)
            <p><strong>Observaciones:</strong> {{ $aplicacionClinica->observaciones }}</p>
        @endif
    </div>
@endsection
