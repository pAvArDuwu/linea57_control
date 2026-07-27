<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);">
                <i class="bi bi-hdd-stack" style="color: var(--primary);"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold" style="color: var(--primary);">
                    {{ $interno->exists ? 'Editar Interno' : 'Nuevo Interno' }}
                </h5>
                <small class="text-muted">Complete los datos del número interno</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="numero_interno" class="form-label fw-semibold">N° Interno <span class="text-danger">*</span></label>
                <input type="text" name="numero_interno" id="numero_interno"
                       class="form-control @error('numero_interno') is-invalid @enderror"
                       value="{{ old('numero_interno', $interno->numero_interno) }}"
                       placeholder="Ej: A-01" required>
                @error('numero_interno')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="fecha_ingreso" class="form-label fw-semibold">Fecha de Ingreso <span class="text-danger">*</span></label>
                <input type="datetime-local" name="fecha_ingreso" id="fecha_ingreso"
                       class="form-control @error('fecha_ingreso') is-invalid @enderror"
                       value="{{ old('fecha_ingreso', $interno->fecha_ingreso ? \Carbon\Carbon::parse($interno->fecha_ingreso)->format('Y-m-d\TH:i') : '') }}"
                       required>
                @error('fecha_ingreso')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                    <option value="disponible" {{ old('estado', $interno->estado ?? 'disponible') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="asignado" {{ old('estado', $interno->estado) === 'asignado' ? 'selected' : '' }}>Asignado</option>
                    <option value="inactivo" {{ old('estado', $interno->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="3"
                          class="form-control @error('observaciones') is-invalid @enderror"
                          placeholder="Observaciones sobre el estado o condición del interno...">{{ old('observaciones', $interno->observaciones) }}</textarea>
                @error('observaciones')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between" style="border-radius: 0 0 16px 16px;">
        <a href="{{ route('interno.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn px-4 py-2"
                style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-check-lg me-2"></i>Guardar Interno
        </button>
    </div>
</div>