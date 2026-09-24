@extends('layouts.app')

@section('contenido')
<div class="container py-4">
    <h1 class="h3 mb-3">Reporte de ventas de entradas</h1>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-auto">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control" value="{{ $desde }}">
        </div>
        <div class="col-auto">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="{{ $hasta }}">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <div class="mb-3">
        <strong>Total del periodo:</strong> Q{{ number_format($totalPeriodo, 2) }}
        &nbsp;|&nbsp;
        <strong>Entradas vendidas:</strong> {{ $cantidadPeriodo }}
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th><th>Tipo de entrada</th><th>Cantidad</th><th>Promoción</th><th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $venta->fecha_venta->format('d/m/Y H:i') }}</td>
                    <td>{{ $venta->tipoEntrada->nombre }}</td>
                    <td>{{ $venta->cantidad }}</td>
                    <td>{{ $venta->promocion?->nombre ?? '—' }}</td>
                    <td>Q{{ number_format($venta->total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Sin ventas en este periodo.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
