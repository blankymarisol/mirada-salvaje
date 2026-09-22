@php $dieta = $dieta ?? null; @endphp

<label for="animal_id">Animal</label>
<select name="animal_id" id="animal_id" required>
    <option value="">Selecciona un animal</option>
    @foreach ($animales as $animal)
        <option value="{{ $animal->id }}" @selected(old('animal_id', $dieta?->animal_id) == $animal->id)>
            {{ $animal->nombre }} ({{ $animal->especie }})
        </option>
    @endforeach
</select>

<label for="inventario_alimento_id">Alimento (inventario)</label>
<select name="inventario_alimento_id" id="inventario_alimento_id" required>
    <option value="">Selecciona un alimento</option>
    @foreach ($alimentos as $alimento)
        <option value="{{ $alimento->id }}" @selected(old('inventario_alimento_id', $dieta?->inventario_alimento_id) == $alimento->id)>
            {{ $alimento->nombre_alimento }} ({{ $alimento->unidad_medida }}) — stock: {{ $alimento->stock_actual }}
        </option>
    @endforeach
</select>

<label for="nombre">Nombre de la dieta</label>
<input type="text" name="nombre" id="nombre" value="{{ old('nombre', $dieta?->nombre) }}" placeholder="Ej. Dieta de mantenimiento" required>

<label for="cantidad_racion">Cantidad por ración</label>
<input type="number" step="0.01" min="0.01" name="cantidad_racion" id="cantidad_racion" value="{{ old('cantidad_racion', $dieta?->cantidad_racion) }}" required>

<label for="frecuencia_diaria">Frecuencia diaria (veces al día)</label>
<input type="number" min="1" max="10" name="frecuencia_diaria" id="frecuencia_diaria" value="{{ old('frecuencia_diaria', $dieta?->frecuencia_diaria ?? 1) }}" required>

<label for="observaciones">Observaciones</label>
<textarea name="observaciones" id="observaciones" rows="3">{{ old('observaciones', $dieta?->observaciones) }}</textarea>

<label style="display:flex; align-items:center; gap:.5rem;">
    <input type="hidden" name="activa" value="0">
    <input type="checkbox" name="activa" value="1" style="width:auto; margin:0;" @checked(old('activa', $dieta?->activa ?? true))>
    Dieta activa
</label>