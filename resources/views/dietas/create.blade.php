@extends('layouts.app')

@section('titulo', 'Nueva dieta')

@section('contenido')
    <h1 style="font-size:1.3rem;">Nueva dieta</h1>

    <div class="card">
        <form action="{{ route('dietas.store') }}" method="POST">
            @csrf
            @include('dietas._form', ['dieta' => null])
            <button type="submit" class="btn">Guardar dieta</button>
            <a href="{{ route('dietas.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection