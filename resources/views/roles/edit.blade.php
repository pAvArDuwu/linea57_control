@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary me-3" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">Editar Rol</h4>
            <p class="text-muted mb-0">Modifica los datos del rol seleccionado</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted" style="font-size: 0.9rem;">Nombre del Rol <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control p-3 bg-light border-0" value="{{ old('name', $role->name) }}" placeholder="Ej: Administrador" required style="border-radius: 10px;">
                        @error('name') <span class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted" style="font-size: 0.9rem;">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select p-3 bg-light border-0" required style="border-radius: 10px;">
                            <option value="activo" {{ old('estado', $role->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $role->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado') <span class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-muted" style="font-size: 0.9rem;">Descripción</label>
                        <textarea name="descripcion" class="form-control p-3 bg-light border-0" rows="3" placeholder="Describe brevemente el rol" style="border-radius: 10px;">{{ old('descripcion', $role->descripcion) }}</textarea>
                        @error('descripcion') <span class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror
                    </div>
                </div>
                <hr class="my-4" style="opacity: 0.1;">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 10px; font-weight: 500;">Cancelar</a>
                    <button type="submit" class="btn px-4 py-2" style="border-radius: 10px; background: linear-gradient(135deg, var(--warning) 0%, #e6a800 100%); color: white; font-weight: 600;">
                        <i class="bi bi-save me-2"></i>Actualizar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection