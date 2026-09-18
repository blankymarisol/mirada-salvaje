@extends('layouts.app')

@section('titulo', 'Inventario de alimentos')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Inventario de alimentos</h1>
        <a href="{{ route('inventario-alimentos.create') }}" class="btn">+ Agregar alimento</a>
    </div>

    @if ($alertaStockBajo->isNotEmpty())
        <div class="errores">
            <strong>⚠️ Stock bajo en {{ $alertaStockBajo->count() }} alimento(s):</strong>
            {{ $alertaStockBajo->pluck('nombre_alimento')->join(', ') }}.
            @if (auth()->check() && auth()->user()->rol?->nombre !== 'admin')
                Solo un usuario admin puede ajustar el stock mínimo.
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