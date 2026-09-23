@extends('layouts.app')

@section('titulo', 'Editar tarea de limpieza')

@section('contenido')
    <div class="card">
        <h1 style="margin:0 0 1rem; font-size:1.3rem;">Editar tarea de limpieza</h1>

        <form method="POST" action="{{ route('limpieza.update', $tarea) }}">
            @method('PUT')
            @include('limpieza._form', ['tarea' => $tarea])
        </form>
    </div>
@endsection