@extends('layouts.app')

@section('titulo', 'Dietas')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Dietas registradas</h1>
        <a href="{{ route('dietas.create') }}" class="btn">+ Nueva dieta</a>
    </div>

    <div class="card">
        @if ($dietas->isEmpty())
            <p class="muted">Todavía no hay dietas registradas.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Dieta</th>
                        <th>Alimento</th>
                        <th>Ración</th>
                        <th>Frecuencia</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dietas as $dieta)
                        <tr>
                            <td>{{ $dieta->animal->nombre }} <span class="muted">({{ $dieta->animal->especie }})</span></td>
                            <td>{{ $dieta->nombre }}</td>
                            <td>{{ $dieta->alimento->nombre_alimento }}</td>
                            <td>{{ $dieta->cantidad_racion }} {{ $dieta->alimento->unidad_medida }}</td>
                            <td>{{ $dieta->frecuencia_diaria }}x / día</td>
                            <td>
                                <span class="badge {{ $dieta->activa ? 'ok' : 'pendiente' }}">
                                    {{ $dieta->activa ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('dietas.show', $dieta) }}">Ver</a>
                                ·
                                <a href="{{ route('dietas.edit', $dieta) }}">Editar</a>
                                ·
                                <form class="inline" action="{{ route('dietas.destroy', $dieta) }}" method="POST" onsubmit="return confirm('¿Eliminar esta dieta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#a33c3c;cursor:pointer;padding:0;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $dietas->links() }}</div>
        @endif
    </div>
@endsection