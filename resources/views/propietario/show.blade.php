@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('propietario.index') }}" class="text-decoration-none">Propietarios</a></li>
                    <li class="breadcrumb-item active">{{ $propietario->nombre }} {{ $propietario->apellido }}</li>
                </ol>
            </nav>
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);">
                                <i class="bi bi-person-vcard" style="color: var(--primary);"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: var(--primary);">Detalle del Propietario</h5>
                                <small class="text-muted">Información completa</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('propietario.edit', $propietario->id) }}" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 8px;">
                                <i class="bi bi-pencil-square me-1"></i>Editar
                            </a>
                            @if(($propietario->estado ?? 'activo') === 'activo')
                                <form action="{{ route('propietario.destroy', $propietario->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar este propietario?')"
                                            class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1">Nombre Completo</div>
                                <div class="fw-semibold">{{ $propietario->nombre }} {{ $propietario->apellido }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1">CI</div>
                                <div class="fw-semibold font-monospace">{{ $propietario->ci }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1">Estado</div>
                                @if(($propietario->estado ?? 'activo') === 'activo')
                                    <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34;">Activo</span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2" style="background: #f0f0f0; color: #6c757d;">Inactivo</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1">Correo Electrónico</div>
                                <div class="fw-semibold">{{ $propietario->correo }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1">Teléfono</div>
                                <div class="fw-semibold">{{ $propietario->telefono ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1">Micros Asignados</div>
                                <div class="fw-semibold">{{ $propietario->micros ? $propietario->micros->count() : 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                    <a href="{{ route('propietario.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
