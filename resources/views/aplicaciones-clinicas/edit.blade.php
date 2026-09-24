@extends('layouts.app')

@section('titulo', 'Editar aplicación clínica')

@section('contenido')
    <h1 style="font-size:1.3rem;">Editar aplicación clínica</h1>

    <div class="card">
        <form action="{{ route('aplicaciones-clinicas.update', $aplicacionClinica) }}" method="POST">
            @csrf
            @method('PUT')
            @include('aplicaciones-clinicas._form', ['aplicacionClinica' => $aplicacionClinica, 'animales' => $animales, 'medicamentos' => $medicamentos, 'vacunas' => $vacunas])
            <button type="submit" class="btn">Actualizar aplicación</button>
            <a href="{{ route('aplicaciones-clinicas.index') }}" class="btn secundario">Cancelar</a>
        </form>
    </div>
@endsection
