@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center"><div class="col-lg-8">
        <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('conductor.index') }}" class="text-decoration-none">Conductores</a></li>
            <li class="breadcrumb-item active">{{ $conductor->nombre }} {{ $conductor->apellido }}</li>
        </ol></nav>
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: var(--primary);">Detalle del Conductor</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('conductor.edit', $conductor->id) }}" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 8px;">Editar</a>
                        @if(($conductor->estado ?? 'activo') === 'activo')
                            <form action="{{ route('conductor.destroy', $conductor->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Desactivar?')" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">Desactivar</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Nombre Completo</div><div class="fw-semibold">{{ $conductor->nombre }} {{ $conductor->apellido }}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">CI</div><div class="fw-semibold font-monospace">{{ $conductor->ci }}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Estado</div>
                        @if(($conductor->estado ?? 'activo') === 'activo')
                            <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34;">Activo</span>
                        @else
                            <span class="badge rounded-pill px-3 py-2" style="background: #f0f0f0; color: #6c757d;">Inactivo</span>
                        @endif
                    </div></div>
                    <div class="col-md-6"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Correo Electrónico</div><div class="fw-semibold">{{ $conductor->correo }}</div></div></div>
                    <div class="col-md-6"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Teléfono</div><div class="fw-semibold">{{ $conductor->telefono }}</div></div></div>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                <a href="{{ route('conductor.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;"><i class="bi bi-arrow-left me-2"></i>Volver</a>
            </div>
        </div>
    </div></div>
</div>
@endsection