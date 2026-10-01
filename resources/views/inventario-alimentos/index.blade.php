@extends('layouts.app')

@section('titulo', 'Inventario de alimentos')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Inventario de alimentos</h1>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('horarios-alimentacion.reporte') }}" class="btn">Reporte de consumo</a>
            <a href="{{ route('inventario-alimentos.create') }}" class="btn">+ Agregar alimento</a>
        </div>
    </div>

    @if ($alertaStockBajo->isNotEmpty())
        <div class="errores">
            <strong>⚠️ Stock bajo: {{ $alertaStockBajo->count() }} alimento(s) están en el mínimo o por debajo</strong>

            <table style="margin-top:10px; background:#ffffff;">
                <thead>
                    <tr>
                        <th>Alimento</th>
                        <th>Stock actual</th>
                        <th>Stock mínimo</th>
                        <th>Faltante para el mínimo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alertaStockBajo->sortByDesc(fn ($a) => $a->stock_minimo - $a->stock_actual) as $bajo)
                        <tr>
                            <td>
                                <a href="{{ route('inventario-alimentos.show', $bajo) }}">{{ $bajo->nombre_alimento }}</a>
                            </td>
                            <td>{{ $bajo->stock_actual }} {{ $bajo->unidad_medida }}</td>
                            <td>{{ $bajo->stock_minimo }} {{ $bajo->unidad_medida }}</td>
                            <td><strong>{{ number_format(max(0, $bajo->stock_minimo - $bajo->stock_actual), 2) }} {{ $bajo->unidad_medida }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (auth()->check() && auth()->user()->rol?->nombre !== 'admin')
                <p style="margin:10px 0 0 0;">Solo un usuario admin puede ajustar el stock mínimo.</p>
            @endif
        </div>
    @endif

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Alimento</th>
                    <th>Tipo</th>
                    <th>Stock actual</th>
                    <th>Stock mínimo</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alimentos as $alimento)
                    <tr>
                        <td>{{ $alimento->nombre_alimento }}</td>
                        <td class="muted">{{ $alimento->tipo ?? '—' }}</td>
                        <td>{{ $alimento->stock_actual }} {{ $alimento->unidad_medida }}</td>
                        <td>{{ $alimento->stock_minimo }} {{ $alimento->unidad_medida }}</td>
                        <td>
                            <span class="badge {{ $alimento->stock_bajo ? 'alerta' : 'ok' }}">
                                {{ $alimento->stock_bajo ? 'Stock bajo' : 'OK' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('inventario-alimentos.show', $alimento) }}">Ver</a>
                            ·
                            <a href="{{ route('inventario-alimentos.edit', $alimento) }}">Editar</a>
                            ·
                            <form class="inline" action="{{ route('inventario-alimentos.destroy', $alimento) }}" method="POST" onsubmit="return confirm('¿Eliminar este alimento del inventario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#a33c3c;cursor:pointer;padding:0;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection