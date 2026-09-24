@extends('layouts.app')

@section('contenido')
<div style="max-width: 400px; margin: 60px auto;">
    <h2>Iniciar sesión</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="email">Correo electrónico</label><br>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password">Contraseña</label><br>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Entrar</button>
    </form>
</div>
@endsection