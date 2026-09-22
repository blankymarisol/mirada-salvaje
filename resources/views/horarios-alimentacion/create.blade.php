@extends('layouts.app')

@section('titulo', 'Programar horario')

@section('contenido')
    <h1 style="font-size:1.3rem;">Programar horario de alimentación</h1>

    <div class="card">
        <form action="{{ route('horarios-alimentacion.store') }}" method="POST">
            @csrf
            @include('horarios-alimentacion._form', ['horario' => null])
            <button type="submit" class="btn">Guardar horario</button>
            <a href="{{ route('horarios-alimentacion.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection