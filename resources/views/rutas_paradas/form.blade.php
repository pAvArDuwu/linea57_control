<div class="row g-3">
    <div class="col-md-6">
        <label for="ruta_id" class="form-label fw-bold">Ruta</label>
        <select name="ruta_id" id="ruta_id" class="form-select @error('ruta_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('ruta_id', $rutaParada->ruta_id) ? '' : 'selected' }}>Seleccione una ruta...</option>
            @foreach($rutas as $ruta)
                <option value="{{ $ruta->id }}" {{ old('ruta_id', $rutaParada->ruta_id) == $ruta->id ? 'selected' : '' }}>
                    {{ $ruta->nombre }} @if($ruta->descripcion) ({{ $ruta->descripcion }}) @endif
                </option>
            @endforeach
        </select>
        @error('ruta_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="parada_id" class="form-label fw-bold">Parada</label>
        <select name="parada_id" id="parada_id" class="form-select @error('parada_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('parada_id', $rutaParada->parada_id) ? '' : 'selected' }}>Seleccione una parada...</option>
            @foreach($paradas as $parada)
                <option value="{{ $parada->id }}" {{ old('parada_id', $rutaParada->parada_id) == $parada->id ? 'selected' : '' }}>
                    {{ $parada->nombre }} ({{ $parada->referencia }})
                </option>
            @endforeach
        </select>
        @error('parada_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="orden" class="form-label fw-bold">Orden</label>
        <input type="number" name="orden" id="orden" class="form-control @error('orden') is-invalid @enderror" value="{{ old('orden', $rutaParada->orden) }}" min="1" required>
        @error('orden')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="sentido" class="form-label fw-bold">Sentido</label>
        <select name="sentido" id="sentido" class="form-select @error('sentido') is-invalid @enderror" required>
            <option value="Ida" {{ old('sentido', $rutaParada->sentido) === 'Ida' ? 'selected' : '' }}>Ida</option>
            <option value="Vuelta" {{ old('sentido', $rutaParada->sentido) === 'Vuelta' ? 'selected' : '' }}>Vuelta</option>
        </select>
        @error('sentido')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="estado" class="form-label fw-bold">Estado</label>
        <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
            <option value="activo" {{ old('estado', $rutaParada->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ old('estado', $rutaParada->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 d-flex justify-content-between mt-4">
        <a href="{{ route('rutas-paradas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar Asignación</button>
    </div>
</div>
