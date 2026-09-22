@extends('layouts.app')

@section('titulo', 'Nuevo alimento')

@section('contenido')
    <h1 style="font-size:1.3rem;">Agregar alimento al inventario</h1>

    <div class="card">
        <form action="{{ route('inventario-alimentos.store') }}" method="POST">
            @csrf

            <label for="nombre_alimento">Nombre del alimento</label>
            <input type="text" name="nombre_alimento" id="nombre_alimento" value="{{ old('nombre_alimento') }}" required>

            <label for="tipo">Tipo</label>
            <input type="text" name="tipo" id="tipo" value="{{ old('tipo') }}" placeholder="concentrado, carne, fruta, heno...">

            <label for="unidad_medida">Unidad de medida</label>
            <input type="text" name="unidad_medida" id="unidad_medida" value="{{ old('unidad_medida', 'kg') }}" required>

            <label for="stock_actual">Stock inicial</label>
            <input type="number" step="0.01" min="0" name="stock_actual" id="stock_actual" value="{{ old('stock_actual', 0) }}" required>

            @if (auth()->check() && auth()->user()->rol?->nombre === 'admin')
                <label for="stock_minimo">Stock mínimo (alerta de stock bajo)</label>
                <input type="number" step="0.01" min="0" name="stock_minimo" id="stock_minimo" value="{{ old('stock_minimo', 0) }}" required>
            @else
                <p class="muted">El stock mínimo lo define un usuario con rol <strong>admin</strong>; se creará en 0 y podrá ajustarlo después.</p>
            @endif

            <button type="submit" class="btn">Guardar alimento</button>
            <a href="{{ route('inventario-alimentos.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection