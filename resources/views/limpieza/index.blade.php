@extends('layouts.app')

@section('titulo', 'Tareas de limpieza')

@section('contenido')
    <div class="toolbar">
        <h1 style="margin:0; font-size:1.3rem;">Tareas de limpieza</h1>
        @if (in_array(auth()->user()?->rol?->nombre, ['limpieza', 'admin']))
            <a href="{{ route('limpieza.create') }}" class="btn">+ Nueva tarea</a>
        @endif
    </div>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    <div class="card">
        <form method="GET" style="display:flex; gap:.75rem; flex-wrap:wrap; align-items:end;">
            <div style="min-width:160px;">
                <label for="f_area">Área</label>
                <select name="area_id" id="f_area">
                    <option value="">Todas las áreas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @selected(request('area_id') == $area->id)>{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:160px;">
                <label for="f_turno">Turno</label>
                <select name="turno_id" id="f_turno">
                    <option value="">Todos los turnos</option>
                    @foreach ($turnos as $turno)
                        <option value="{{ $turno->id }}" @selected(request('turno_id') == $turno->id)>{{ $turno->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:160px;">
                <label for="f_estado">Estado</label>
                <select name="estado" id="f_estado">
                    <option value="">Todos los estados</option>
                    @foreach (['pendiente', 'en_progreso', 'completada'] as $estado)
                        <option value="{{ $estado }}" @selected(request('estado') == $estado)>{{ ucfirst(str_replace('_', ' ', $estado)) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn secundario">Filtrar</button>
        </form>
    </div>

    <div class="card">
        @if ($tareas->isEmpty())
            <p class="muted">No hay tareas registradas todavía.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Área</th>
                        <th>Turno</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Verificado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tareas as $tarea)
                        <tr>
                            <td>{{ $tarea->fecha->format('d/m/Y') }}</td>
                            <td>{{ $tarea->area->nombre }}</td>
                            <td>{{ $tarea->turno->nombre }}</td>
                            <td>{{ $tarea->descripcion }}</td>
                            <td>
                                <span class="badge {{ $tarea->estado === 'completada' ? 'ok' : ($tarea->estado === 'en_progreso' ? 'pendiente' : 'alerta') }}">
                                    {{ ucfirst(str_replace('_', ' ', $tarea->estado)) }}
                                </span>
                            </td>
                            <td>{{ $tarea->verificado ? 'Sí' : 'No' }}</td>
                            <td>
                                @if (in_array(auth()->user()?->rol?->nombre, ['limpieza', 'admin']))
                                    <a href="{{ route('limpieza.edit', $tarea) }}">Editar</a>
                                    @unless ($tarea->verificado)
                                        ·
                                        <form class="inline" method="POST" action="{{ route('limpieza.verificar', $tarea) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn" style="padding:.2rem .6rem; background:#276b27;">Verificar</button>
                                        </form>
                                    @endunless
                                    ·
                                    <form class="inline" method="POST" action="{{ route('limpieza.destroy', $tarea) }}" onsubmit="return confirm('¿Eliminar esta tarea?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:none;border:none;color:#a33c3c;cursor:pointer;padding:0;">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $tareas->links() }}</div>
        @endif
    </div>
@endsection