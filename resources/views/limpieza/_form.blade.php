@csrf

<div>
    <label for="area_id">Área</label>
    <select name="area_id" id="area_id">
        <option value="">Selecciona un área</option>
        @foreach ($areas as $area)
            <option value="{{ $area->id }}" @selected(old('area_id', $tarea->area_id ?? '') == $area->id)>
                {{ $area->nombre }}
            </option>
        @endforeach
    </select>
    @error('area_id') <p class="muted" style="color:#9c2b2b;">{{ $message }}</p> @enderror
</div>

<div>
    <label for="turno_id">Turno</label>
    <select name="turno_id" id="turno_id">
        <option value="">Selecciona un turno</option>
        @foreach ($turnos as $turno)
            <option value="{{ $turno->id }}" @selected(old('turno_id', $tarea->turno_id ?? '') == $turno->id)>
                {{ $turno->nombre }}
            </option>
        @endforeach
    </select>
    @error('turno_id') <p class="muted" style="color:#9c2b2b;">{{ $message }}</p> @enderror
</div>

<div>
    <label for="descripcion">Descripción</label>
    <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion', $tarea->descripcion ?? '') }}">
    @error('descripcion') <p class="muted" style="color:#9c2b2b;">{{ $message }}</p> @enderror
</div>

<div>
    <label for="fecha">Fecha</label>
    <input type="date" name="fecha" id="fecha" value="{{ old('fecha', isset($tarea->fecha) ? $tarea->fecha->format('Y-m-d') : '') }}">
    @error('fecha') <p class="muted" style="color:#9c2b2b;">{{ $message }}</p> @enderror
</div>

<div>
    <label for="estado">Estado</label>
    <select name="estado" id="estado">
        @foreach (['pendiente', 'en_progreso', 'completada'] as $estado)
            <option value="{{ $estado }}" @selected(old('estado', $tarea->estado ?? 'pendiente') == $estado)>
                {{ ucfirst(str_replace('_', ' ', $estado)) }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="observaciones">Observaciones</label>
    <textarea name="observaciones" id="observaciones" rows="3">{{ old('observaciones', $tarea->observaciones ?? '') }}</textarea>
</div>

<button type="submit" class="btn">Guardar</button>