@extends('layouts.app')

@section('contenido')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Promociones</h1>
        <a href="{{ route('admin.promociones.create') }}" class="btn btn-primary">Nueva</a>
    </div>

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr><th>Nombre</th><th>Descuento</th><th>Vigencia</th><th>Activa</th><th></th></tr>
        </thead>
        <tbody>
            @foreach ($promociones as $promo)
                <tr>
                    <td>{{ $promo->nombre }}</td>
                    <td>{{ $promo->descuento_porcentaje }}%</td>
                    <td>{{ $promo->vigente_desde->format('d/m/Y') }} - {{ $promo->vigente_hasta->format('d/m/Y') }}</td>
                    <td>{{ $promo->estaVigente() ? 'Vigente' : 'No vigente' }}</td>
                    <td>
                        <a href="{{ route('admin.promociones.edit', $promo) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                        <form action="{{ route('admin.promociones.destroy', $promo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta promoción?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $promociones->links() }}
</div>
@endsection
