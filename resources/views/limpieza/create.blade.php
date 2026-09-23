@extends('layouts.app')

@section('titulo', 'Nueva tarea de limpieza')

@section('contenido')
    <div class="card">
        <h1 style="margin:0 0 1rem; font-size:1.3rem;">Nueva tarea de limpieza</h1>

        <form method="POST" action="{{ route('limpieza.store') }}">
            @include('limpieza._form', ['tarea' => null])
        </form>
    </div>
@endsection