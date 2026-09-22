@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">{{ $tipoEntrada->exists ? 'Editar' : 'Nuevo' }} tipo de entrada</h1>

    <form action="{{ $tipoEntrada->exists ? route('admin.tipos-entrada.update', $tipoEntrada) : route('admin.tipos-entrada.store') }}" method="POST">
        @csrf
        @if ($tipoEntrada->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $tipoEntrada->nombre) }}" required>
            @error('nombre') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Precio (Q)</label>
            <input type="number" step="0.01" min="0" name="precio" class="form-control" value="{{ old('precio', $tipoEntrada->precio) }}" required>
            @error('precio') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="activo" value="1" class="form-check-input" id="activo"
                   {{ old('activo', $tipoEntrada->activo ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">Activo</label>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('admin.tipos-entrada.index') }}" class="btn btn-link">Cancelar</a>
    </form>
</div>
@endsection
