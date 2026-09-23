@extends('layouts.app')

@section('contenido')
<div class="container py-4">
    <h1 class="mb-3">Entradas y promociones</h1>

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <h2 class="h5">Tipos de entrada disponibles</h2>
            <table class="table table-bordered">
                <thead>
                    <tr><th>Tipo</th><th>Precio</th></tr>
                </thead>
                <tbody>
                    @foreach ($tiposEntrada as $tipo)
                        <tr>
                            <td>{{ $tipo->nombre }}</td>
                            <td>Q{{ number_format($tipo->precio, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h2 class="h5">Promociones vigentes</h2>
            @forelse ($promocionesVigentes as $promo)
                <div class="border rounded p-2 mb-2">
                    <strong>{{ $promo->nombre }}</strong> — {{ $promo->descuento_porcentaje }}% de descuento
                    <br><small>Válida hasta {{ $promo->vigente_hasta->format('d/m/Y') }}</small>
                </div>
            @empty
                <p class="text-muted">No hay promociones activas por el momento.</p>
            @endforelse
        </div>

        <div class="col-md-6">
            <h2 class="h5">Comprar entrada (simulado, sin pago real)</h2>
            <form action="{{ route('entradas.comprar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tipo de entrada</label>
                    <select name="tipo_entrada_id" class="form-select" required>
                        <option value="">Selecciona...</option>
                        @foreach ($tiposEntrada as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }} (Q{{ number_format($tipo->precio, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" min="1" max="20" value="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Promoción (opcional)</label>
                    <select name="promocion_id" class="form-select">
                        <option value="">Sin promoción</option>
                        @foreach ($promocionesVigentes as $promo)
                            <option value="{{ $promo->id }}">{{ $promo->nombre }} (-{{ $promo->descuento_porcentaje }}%)</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Confirmar compra</button>
            </form>
        </div>
    </div>
</div>
@endsection
