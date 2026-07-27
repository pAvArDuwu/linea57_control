<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);">
                <i class="bi bi-bus-front" style="color: #e65100;"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold" style="color: var(--primary);">{{ $micro->exists ? 'Editar Micro' : 'Nuevo Micro' }}</h5>
                <small class="text-muted">Complete los datos del vehículo</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        {{-- Sección 1: Asignaciones --}}
        <h6 class="fw-bold mb-3 text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="bi bi-link-45deg me-1"></i> Asignaciones
        </h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="propietario_id" class="form-label fw-semibold">Propietario <span class="text-danger">*</span></label>
                <select name="propietario_id" id="propietario_id" class="form-select @error('propietario_id') is-invalid @enderror" required>
                    <option value="">Seleccione un propietario...</option>
                    @foreach($propietarios as $p)
                        <option value="{{ $p->id }}" {{ old('propietario_id', $micro->propietario_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }} {{ $p->apellido }} — CI: {{ $p->ci }}
                        </option>
                    @endforeach
                </select>
                @error('propietario_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="interno_id" class="form-label fw-semibold">N° Interno</label>
                <select name="interno_id" id="interno_id" class="form-select @error('interno_id') is-invalid @enderror">
                    <option value="">Sin interno asignado</option>
                    @foreach($internos as $i)
                        <option value="{{ $i->id }}" {{ old('interno_id', $micro->interno_id) == $i->id ? 'selected' : '' }}>
                            {{ $i->numero_interno }} ({{ ucfirst($i->estado) }})
                        </option>
                    @endforeach
                </select>
                @error('interno_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="my-4">

        {{-- Sección 2: Datos del Vehículo --}}
        <h6 class="fw-bold mb-3 text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="bi bi-card-list me-1"></i> Datos del Vehículo
        </h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label for="placa" class="form-label fw-semibold">Placa <span class="text-danger">*</span></label>
                <input type="text" name="placa" id="placa"
                       class="form-control @error('placa') is-invalid @enderror"
                       value="{{ old('placa', $micro->placa) }}"
                       placeholder="Ej: 1234-ABC" required>
                @error('placa')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="marca" class="form-label fw-semibold">Marca <span class="text-danger">*</span></label>
                <input type="text" name="marca" id="marca"
                       class="form-control @error('marca') is-invalid @enderror"
                       value="{{ old('marca', $micro->marca) }}"
                       placeholder="Ej: Toyota" required>
                @error('marca')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="modelo" class="form-label fw-semibold">Modelo <span class="text-danger">*</span></label>
                <input type="text" name="modelo" id="modelo"
                       class="form-control @error('modelo') is-invalid @enderror"
                       value="{{ old('modelo', $micro->modelo) }}"
                       placeholder="Ej: Coaster" required>
                @error('modelo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="chasis" class="form-label fw-semibold">Chasis</label>
                <input type="text" name="chasis" id="chasis"
                       class="form-control @error('chasis') is-invalid @enderror"
                       value="{{ old('chasis', $micro->chasis) }}"
                       placeholder="N° de chasis">
                @error('chasis')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="anio_fabricacion" class="form-label fw-semibold">Año de Fabricación</label>
                <input type="number" name="anio_fabricacion" id="anio_fabricacion"
                       class="form-control @error('anio_fabricacion') is-invalid @enderror"
                       value="{{ old('anio_fabricacion', $micro->anio_fabricacion) }}"
                       placeholder="Ej: 2018" min="1980" max="{{ date('Y') }}">
                @error('anio_fabricacion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label for="capacidad_pasajeros" class="form-label fw-semibold">Capacidad <span class="text-danger">*</span></label>
                <input type="number" name="capacidad_pasajeros" id="capacidad_pasajeros"
                       class="form-control @error('capacidad_pasajeros') is-invalid @enderror"
                       value="{{ old('capacidad_pasajeros', $micro->capacidad_pasajeros) }}"
                       placeholder="30" min="1" required>
                @error('capacidad_pasajeros')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                    <option value="activo" {{ old('estado', $micro->estado ?? 'activo') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $micro->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between" style="border-radius: 0 0 16px 16px;">
        <a href="{{ route('micro.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn px-4 py-2"
                style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-check-lg me-2"></i>Guardar Micro
        </button>
    </div>
</div>