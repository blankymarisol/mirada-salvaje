@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">{{ $promocion->exists ? 'Editar' : 'Nueva' }} promoción</h1>

    <form action="{{ $promocion->exists ? route('admin.promociones.update', $promocion) : route('admin.promociones.store') }}" method="POST">
        @csrf
        @if ($promocion->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $promocion->nombre) }}" required>
            @error('nombre') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Descuento (%)</label>
            <input type="number" min="1" max="100" name="descuento_porcentaje" class="form-control" value="{{ old('descuento_porcentaje', $promocion->descuento_porcentaje) }}" required>
            @error('descuento_porcentaje') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Vigente desde</label>
                <input type="date" name="vigente_desde" class="form-control" value="{{ old('vigente_desde', optional($promocion->vigente_desde)->format('Y-m-d')) }}" required>
                @error('vigente_desde') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Vigente hasta</label>
                <input type="date" name="vigente_hasta" class="form-control" value="{{ old('vigente_hasta', optional($promocion->vigente_hasta)->format('Y-m-d')) }}" required>
                @error('vigente_hasta') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="activa" value="1" class="form-check-input" id="activa"
                   {{ old('activa', $promocion->activa ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="activa">Activa</label>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('admin.promociones.index') }}" class="btn btn-link">Cancelar</a>
    </form>
</div>
@endsection
