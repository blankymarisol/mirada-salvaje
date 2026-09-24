@extends('layouts.app')

@section('titulo', 'Nueva aplicación clínica')

@section('contenido')
    <h1 style="font-size:1.3rem;">Nueva aplicación clínica</h1>

    <div class="card">
        <form action="{{ route('aplicaciones-clinicas.store') }}" method="POST">
            @csrf
            @include('aplicaciones-clinicas._form', ['aplicacionClinica' => null, 'animales' => $animales, 'medicamentos' => $medicamentos, 'vacunas' => $vacunas])
            <button type="submit" class="btn">Guardar aplicación</button>
            <a href="{{ route('aplicaciones-clinicas.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection
