@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Tipos de entrada</h1>
        <a href="{{ route('admin.tipos-entrada.create') }}" class="btn btn-primary">Nuevo</a>
    </div>

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr><th>Nombre</th><th>Precio</th><th>Activo</th><th></th></tr>
        </thead>
        <tbody>
            @foreach ($tiposEntrada as $tipo)
                <tr>
                    <td>{{ $tipo->nombre }}</td>
                    <td>Q{{ number_format($tipo->precio, 2) }}</td>
                    <td>{{ $tipo->activo ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.tipos-entrada.edit', $tipo) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                        <form action="{{ route('admin.tipos-entrada.destroy', $tipo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este tipo de entrada?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tiposEntrada->links() }}
</div>
@endsection
