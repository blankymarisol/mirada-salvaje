@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
<div class="card">
    <h1 style="margin-top:0;">Bienvenido(a), {{ auth()->user()->name }}</h1>
    <p class="muted">Rol: {{ auth()->user()->rol?->nombre ?? 'sin rol' }}</p>
    <p>Este es el panel interno de <strong>Mirada Salvaje</strong>. Elige un módulo para empezar.</p>
</div>

<div class="card">
    <h2 style="margin-top:0; font-size:1.1rem;">Módulos del sistema</h2>
    <table>
        <tbody>
            <tr>
                <td><strong>Gestión de alimentación</strong><br><span class="muted">Dietas, horarios e inventario</span></td>
                <td style="text-align:right;">
                    <a href="{{ route('dietas.index') }}" class="btn secundario">Dietas</a>
                    <a href="{{ route('horarios-alimentacion.index') }}" class="btn secundario">Horarios</a>
                    <a href="{{ route('inventario-alimentos.index') }}" class="btn secundario">Inventario</a>
                </td>
            </tr>
            <tr>
                <td><strong>Gestión de limpieza</strong><br><span class="muted">Tareas por área y turno</span></td>
                <td style="text-align:right;"><a href="{{ route('limpieza.index') }}" class="btn">Ver limpieza</a></td>
            </tr>
            <tr>
                <td><strong>Control clínico</strong><br><span class="muted">Aplicaciones y alertas de vacunación</span></td>
                <td style="text-align:right;"><a href="{{ route('aplicaciones-clinicas.index') }}" class="btn">Ver clínica</a></td>
            </tr>
            <tr>
                <td><strong>Entradas y promociones</strong><br><span class="muted">Venta pública y panel admin</span></td>
                <td style="text-align:right;"><a href="{{ route('entradas.publico') }}" class="btn">Ver entradas</a></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection