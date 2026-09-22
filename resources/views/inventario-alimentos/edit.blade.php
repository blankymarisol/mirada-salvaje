@extends('layouts.app')

@section('titulo', 'Editar alimento')

@section('contenido')
    <h1 style="font-size:1.3rem;">Editar: {{ $alimento->nombre_alimento }}</h1>

    <div class="card">
        <form action="{{ route('inventario-alimentos.update', $alimento) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nombre_alimento">Nombre del alimento</label>
            <input type="text" name="nombre_alimento" id="nombre_alimento" value="{{ old('nombre_alimento', $alimento->nombre_alimento) }}" required>

            <label for="tipo">Tipo</label>
            <input type="text" name="tipo" id="tipo" value="{{ old('tipo', $alimento->tipo) }}">

            <label for="unidad_medida">Unidad de medida</label>
            <input type="text" name="unidad_medida" id="unidad_medida" value="{{ old('unidad_medida', $alimento->unidad_medida) }}" required>

            <label for="stock_actual">Stock actual</label>
            <input type="number" step="0.01" min="0" name="stock_actual" id="stock_actual" value="{{ old('stock_actual', $alimento->stock_actual) }}" required>

            @if (auth()->check() && auth()->user()->rol?->nombre === 'admin')
                <label for="stock_minimo">Stock mínimo (alerta de stock bajo)</label>
                <input type="number" step="0.01" min="0" name="stock_minimo" id="stock_minimo" value="{{ old('stock_minimo', $alimento->stock_minimo) }}" required>
            @else
                <p class="muted">
                    Stock mínimo actual: <strong>{{ $alimento->stock_minimo }} {{ $alimento->unidad_medida }}</strong>.
                    Solo un usuario con rol <strong>admin</strong> puede cambiarlo.
                </p>
            @endif

            <button type="submit" class="btn">Guardar cambios</button>
            <a href="{{ route('inventario-alimentos.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection