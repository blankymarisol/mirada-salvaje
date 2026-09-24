@php
    $aplicacionClinica = $aplicacionClinica ?? null;
    $tipoActual = old('tipo', $aplicacionClinica?->tipo ?? 'medicamento');
@endphp

<label for="animal_id">Animal</label>
<select name="animal_id" id="animal_id">
    <option value="">Selecciona un animal</option>
    @foreach ($animales as $animal)
        <option value="{{ $animal->id }}" @selected(old('animal_id', $aplicacionClinica?->animal_id) == $animal->id)>
            {{ $animal->nombre }} ({{ $animal->especie }})
        </option>
    @endforeach
</select>

<label for="tipo">Tipo</label>
<select name="tipo" id="tipo">
    <option value="medicamento" @selected($tipoActual === 'medicamento')>Medicamento</option>
    <option value="vacuna" @selected($tipoActual === 'vacuna')>Vacuna</option>
</select>

<label for="medicamento_id">Medicamento</label>
<select name="medicamento_id" id="medicamento_id" @if ($tipoActual !== 'medicamento') style="display:none;" @endif>
    <option value="">Selecciona un medicamento</option>
    @foreach ($medicamentos as $medicamento)
        <option value="{{ $medicamento->id }}" @selected(old('medicamento_id', $aplicacionClinica?->medicamento_id) == $medicamento->id)>
            {{ $medicamento->nombre }}
        </option>
    @endforeach
</select>

<label for="vacuna_id">Vacuna</label>
<select name="vacuna_id" id="vacuna_id" @if ($tipoActual !== 'vacuna') style="display:none;" @endif>
    <option value="">Selecciona una vacuna</option>
    @foreach ($vacunas as $vacuna)
        <option value="{{ $vacuna->id }}" @selected(old('vacuna_id', $aplicacionClinica?->vacuna_id) == $vacuna->id)>
            {{ $vacuna->nombre }}
        </option>
    @endforeach
</select>

<label for="fecha_aplicacion">Fecha de aplicación</label>
<input type="date" name="fecha_aplicacion" id="fecha_aplicacion" value="{{ old('fecha_aplicacion', $aplicacionClinica?->fecha_aplicacion?->format('Y-m-d')) }}">

<label for="dosis">Dosis</label>
<input type="text" name="dosis" id="dosis" value="{{ old('dosis', $aplicacionClinica?->dosis) }}" placeholder="Ej. 1 dosis, 10 mg/kg">

<label for="observaciones">Observaciones</label>
<textarea name="observaciones" id="observaciones" rows="3" placeholder="Detalles clínicos o evolución del animal...">{{ old('observaciones', $aplicacionClinica?->observaciones) }}</textarea>

<label for="proxima_dosis">Próxima dosis</label>
<input type="date" name="proxima_dosis" id="proxima_dosis" value="{{ old('proxima_dosis', $aplicacionClinica?->proxima_dosis?->format('Y-m-d')) }}">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoSelect = document.getElementById('tipo');
        const medicamentoSelect = document.getElementById('medicamento_id');
        const vacunaSelect = document.getElementById('vacuna_id');

        function actualizarCamposTipo() {
            const tipo = tipoSelect.value;
            if (tipo === 'vacuna') {
                medicamentoSelect.style.display = 'none';
                vacunaSelect.style.display = 'block';
                medicamentoSelect.disabled = true;
                vacunaSelect.disabled = false;
                medicamentoSelect.value = '';
            } else {
                vacunaSelect.style.display = 'none';
                medicamentoSelect.style.display = 'block';
                vacunaSelect.disabled = true;
                medicamentoSelect.disabled = false;
                vacunaSelect.value = '';
            }
        }

        tipoSelect.addEventListener('change', actualizarCamposTipo);
        actualizarCamposTipo();
    });
</script>
