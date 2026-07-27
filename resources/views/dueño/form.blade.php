<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);">
                <i class="bi bi-person-vcard" style="color: var(--primary);"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold" style="color: var(--primary);">
                    {{ $dueño->exists ? 'Editar Propietario' : 'Nuevo Propietario' }}
                </h5>
                <small class="text-muted">Complete los datos del propietario</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $dueño->nombre) }}"
                       placeholder="Nombre del propietario" required>
                @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="apellido" class="form-label fw-semibold">Apellido <span class="text-danger">*</span></label>
                <input type="text" name="apellido" id="apellido"
                       class="form-control @error('apellido') is-invalid @enderror"
                       value="{{ old('apellido', $dueño->apellido) }}"
                       placeholder="Apellido del propietario" required>
                @error('apellido')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="ci" class="form-label fw-semibold">CI <span class="text-danger">*</span></label>
                <input type="text" name="ci" id="ci"
                       class="form-control @error('ci') is-invalid @enderror"
                       value="{{ old('ci', $dueño->ci) }}"
                       placeholder="Carnet de identidad" required>
                @error('ci')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                <input type="text" name="telefono" id="telefono"
                       class="form-control @error('telefono') is-invalid @enderror"
                       value="{{ old('telefono', $dueño->telefono) }}"
                       placeholder="Ej: 77712345">
                @error('telefono')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                    <option value="activo" {{ old('estado', $dueño->estado ?? 'activo') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $dueño->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="correo" class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" name="correo" id="correo"
                       class="form-control @error('correo') is-invalid @enderror"
                       value="{{ old('correo', $dueño->correo) }}"
                       placeholder="correo@ejemplo.com" required>
                @error('correo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between" style="border-radius: 0 0 16px 16px;">
        <a href="{{ route('propietario.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn px-4 py-2"
                style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-check-lg me-2"></i>Guardar Propietario
        </button>
    </div>
</div>