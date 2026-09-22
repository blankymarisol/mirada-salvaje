@extends('layouts.app')

@section('titulo', $alimento->nombre_alimento)

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">{{ $alimento->nombre_alimento }}</h1>
        <div>
            <a href="{{ route('inventario-alimentos.edit', $alimento) }}" class="btn secundario">Editar</a>
            <a href="{{ route('inventario-alimentos.index') }}" class="btn secundario">Volver</a>
        </div>
    </div>

    <div class="card">
        <p><strong>Tipo:</strong> {{ $alimento->tipo ?? '—' }}</p>
        <p><strong>Stock actual:</strong> {{ $alimento->stock_actual }} {{ $alimento->unidad_medida }}</p>
        <p><strong>Stock mínimo:</strong> {{ $alimento->stock_minimo }} {{ $alimento->unidad_medida }}</p>
        <p><strong>Estado:</strong>
            <span class="badge {{ $alimento->stock_bajo ? 'alerta' : 'ok' }}">
                {{ $alimento->stock_bajo ? 'Stock bajo' : 'OK' }}
            </span>
        </p>
    </div>

    <div class="card">
        <h2 style="margin-top:0; font-size:1.05rem;">Dietas que usan este alimento</h2>
        @if ($alimento->dietas->isEmpty())
            <p class="muted">Ninguna dieta usa este alimento todavía.</p>
        @else
            <table>
                <thead><tr><th>Animal</th><th>Dieta</th><th>Ración</th></tr></thead>
                <tbody>
                    @foreach ($alimento->dietas as $dieta)
                        <tr>
                            <td>{{ $dieta->animal->nombre }}</td>
                            <td>{{ $dieta->nombre }}</td>
                            <td>{{ $dieta->cantidad_racion }} {{ $alimento->unidad_medida }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection