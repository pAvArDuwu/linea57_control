@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center"><div class="col-lg-9">
        <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('micro.index') }}" class="text-decoration-none">Micros</a></li>
            <li class="breadcrumb-item active">{{ $micro->placa }}</li>
        </ol></nav>
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: var(--primary);">Detalle del Micro</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('micro.edit', $micro->id) }}" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 8px;">Editar</a>
                        @if($micro->estado === 'activo')
                            <form action="{{ route('micro.destroy', $micro->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Desactivar este micro?')" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">Desactivar</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Placa</div><div class="fw-bold fs-5 font-monospace">{{ $micro->placa }}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Marca</div><div class="fw-semibold">{{ $micro->marca }}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Modelo</div><div class="fw-semibold">{{ $micro->modelo }}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Estado</div>
                        @if($micro->estado === 'activo')
                            <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34;">Activo</span>
                        @else
                            <span class="badge rounded-pill px-3 py-2" style="background: #f0f0f0; color: #6c757d;">Inactivo</span>
                        @endif
                    </div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Propietario</div><div class="fw-semibold">{{ $micro->propietario ? $micro->propietario->nombre . ' ' . $micro->propietario->apellido : '—' }}</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">N° Interno</div><div class="fw-semibold font-monospace">{{ $micro->interno->numero_interno ?? '—' }}</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Capacidad</div><div class="fw-semibold">{{ $micro->capacidad_pasajeros }} pasajeros</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Chasis</div><div class="fw-semibold font-monospace">{{ $micro->chasis ?? '—' }}</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Año Fabricación</div><div class="fw-semibold">{{ $micro->anio_fabricacion ?? '—' }}</div></div></div>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                <a href="{{ route('micro.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;"><i class="bi bi-arrow-left me-2"></i>Volver</a>
            </div>
        </div>
    </div></div>
</div>
@endsection
