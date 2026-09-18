@extends('layouts.app')

@section('titulo', 'Editar horario')

@section('contenido')
    <h1 style="font-size:1.3rem;">Editar horario</h1>

    <div class="card">
        <form action="{{ route('horarios-alimentacion.update', $horario) }}" method="POST">
            @csrf
            @method('PUT')
            @include('horarios-alimentacion._form')
            <button type="submit" class="btn">Guardar cambios</button>
            <a href="{{ route('horarios-alimentacion.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection