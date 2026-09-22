@extends('layouts.app')

@section('titulo', 'Editar dieta')

@section('contenido')
    <h1 style="font-size:1.3rem;">Editar dieta</h1>

    <div class="card">
        <form action="{{ route('dietas.update', $dieta) }}" method="POST">
            @csrf
            @method('PUT')
            @include('dietas._form')
            <button type="submit" class="btn">Guardar cambios</button>
            <a href="{{ route('dietas.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection