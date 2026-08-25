@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center"><div class="col-lg-8">
        <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('interno.index') }}" class="text-decoration-none">Internos</a></li>
            <li class="breadcrumb-item active">{{ $interno->numero_interno }}</li>
        </ol></nav>
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: var(--primary);">Detalle del Interno</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('interno.edit', $interno->id) }}" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 8px;">
                            <i class="bi bi-pencil-square me-1"></i>Editar
                        </a>
                        @if($interno->estado !== 'inactivo')
                            <form action="{{ route('interno.destroy', $interno->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar este interno?')" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">N° Interno</div><div class="fw-bold fs-5 font-monospace">{{ $interno->numero_interno }}</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Fecha Ingreso</div><div class="fw-semibold">{{ \Carbon\Carbon::parse($interno->fecha_ingreso)->format('d/m/Y H:i') }}</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Estado</div>
                        <span class="badge rounded-pill px-3 py-2" style="background: {{ $interno->estado_badge['bg'] }}; color: {{ $interno->estado_badge['color'] }}; font-weight: 500;">{{ $interno->estado_badge['label'] }}</span>
                    </div></div>
                    <div class="col-12"><div class="p-3 rounded-3" style="background: #f8f9fc;"><div class="text-muted small mb-1">Observaciones</div><div class="text-secondary">{{ $interno->observaciones ?: 'Sin observaciones.' }}</div></div></div>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                <a href="{{ route('interno.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;"><i class="bi bi-arrow-left me-2"></i>Volver</a>
            </div>
        </div>
    </div></div>
</div>
@endsection
