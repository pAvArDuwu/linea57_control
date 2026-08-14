{{-- Formulario compartido: create y edit de Turno (catálogo) --}}
<div class="row g-4">
    {{-- Nombre del turno --}}
    <div class="col-md-12">
        <label for="nombre" class="form-label fw-semibold">
            <i class="bi bi-clock me-1 text-primary"></i>Turno
        </label>
        <select name="nombre" id="nombre"
                class="form-select @error('nombre') is-invalid @enderror"
                style="border-radius: 10px;">
            <option value="">— Seleccionar turno —</option>
            <option value="mañana" {{ old('nombre', $turno->nombre ?? '') === 'mañana' ? 'selected' : '' }}>
                ☀️ Mañana
            </option>
            <option value="tarde" {{ old('nombre', $turno->nombre ?? '') === 'tarde' ? 'selected' : '' }}>
                🌤️ Tarde
            </option>
            <option value="noche" {{ old('nombre', $turno->nombre ?? '') === 'noche' ? 'selected' : '' }}>
                🌙 Noche
            </option>
        </select>
        @error('nombre')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Hora de inicio --}}
    <div class="col-md-6">
        <label for="hora_inicio" class="form-label fw-semibold">
            <i class="bi bi-play-circle me-1 text-success"></i>Hora de inicio
        </label>
        <input type="time" name="hora_inicio" id="hora_inicio"
               class="form-control @error('hora_inicio') is-invalid @enderror"
               value="{{ old('hora_inicio', isset($turno->hora_inicio) ? \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') : '') }}"
               style="border-radius: 10px;">
        @error('hora_inicio')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Hora de fin --}}
    <div class="col-md-6">
        <label for="hora_fin" class="form-label fw-semibold">
            <i class="bi bi-stop-circle me-1" style="color: var(--accent);"></i>Hora de fin
        </label>
        <input type="time" name="hora_fin" id="hora_fin"
               class="form-control @error('hora_fin') is-invalid @enderror"
               value="{{ old('hora_fin', isset($turno->hora_fin) ? \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') : '') }}"
               style="border-radius: 10px;">
        @error('hora_fin')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Descripción --}}
    <div class="col-md-12">
        <label for="descripcion" class="form-label fw-semibold">
            <i class="bi bi-chat-text me-1 text-secondary"></i>Descripción <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input type="text" name="descripcion" id="descripcion"
               class="form-control @error('descripcion') is-invalid @enderror"
               value="{{ old('descripcion', $turno->descripcion ?? '') }}"
               placeholder="Ej. Turno de mañana, inicia a las 5am"
               style="border-radius: 10px;" maxlength="255">
        @error('descripcion')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Estado --}}
    <div class="col-md-12">
        <label for="estado" class="form-label fw-semibold">
            <i class="bi bi-toggle-on me-1 text-primary"></i>Estado
        </label>
        <select name="estado" id="estado"
                class="form-select @error('estado') is-invalid @enderror"
                style="border-radius: 10px;">
            <option value="activo"   {{ old('estado', $turno->estado ?? 'activo') === 'activo'   ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ old('estado', $turno->estado ?? 'activo') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
        @error('estado')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Botones --}}
    <div class="col-md-12 mt-2 d-flex justify-content-between">
        <a href="{{ route('turno.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i>Cancelar
        </a>
        <button type="submit" class="btn px-5 fw-semibold" style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; box-shadow: 0 4px 15px rgba(11,60,120,0.2);">
            <i class="bi bi-check-lg me-1"></i>Guardar
        </button>
    </div>
</div>