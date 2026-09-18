@php $horario = $horario ?? null; @endphp

<label for="dieta_id">Dieta</label>
<select name="dieta_id" id="dieta_id" required>
    <option value="">Selecciona una dieta</option>
    @foreach ($dietas as $dieta)
        <option value="{{ $dieta->id }}" @selected(old('dieta_id', $horario?->dieta_id) == $dieta->id)>
            {{ $dieta->animal->nombre }} — {{ $dieta->nombre }}
        </option>
    @endforeach
</select>

<label for="fecha">Fecha</label>
<input type="date" name="fecha" id="fecha" value="{{ old('fecha', $horario?->fecha?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>

<label for="hora_programada">Hora programada</label>
<input type="time" name="hora_programada" id="hora_programada" value="{{ old('hora_programada', $horario ? \Illuminate\Support\Carbon::parse($horario->hora_programada)->format('H:i') : '') }}" required>

<label for="cantidad">Cantidad (opcional — si se deja vacío se usa la ración estándar de la dieta)</label>
<input type="number" step="0.01" min="0.01" name="cantidad" id="cantidad" value="{{ old('cantidad', $horario?->cantidad) }}">