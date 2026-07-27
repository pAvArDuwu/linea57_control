@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('ruta.index') }}" class="text-decoration-none">Rutas</a></li>
                    <li class="breadcrumb-item active">{{ $ruta->nombre }}</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #fde8ec 0%, #f8c4ce 100%);">
                                <i class="bi bi-signpost-2-fill" style="color: var(--accent);"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: var(--primary);">Detalles de la Ruta</h5>
                                <small class="text-muted">Información general y recorrido de paradas</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ruta.edit', $ruta->id) }}" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 8px;">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <form action="{{ route('ruta.destroy', $ruta->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Está seguro de eliminar esta ruta?')"
                                        class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    {{-- General Info --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-signpost-2 me-1"></i>Nombre de la Ruta</div>
                                <div class="fw-semibold fs-5">{{ $ruta->nombre }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-arrow-left-right me-1"></i>Sentido</div>
                                <div>
                                    @if($ruta->sentido === 'Ida')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e8f0fe; color: #1565c0; font-weight: 500;">
                                            <i class="bi bi-arrow-right me-1"></i>Ida
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #f3e5f5; color: #7b1fa2; font-weight: 500;">
                                            <i class="bi bi-arrow-left me-1"></i>Vuelta
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-toggle-on me-1"></i>Estado</div>
                                <div>
                                    @if($ruta->estado === 'activo')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34; font-weight: 500;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #fce8e6; color: #c62828; font-weight: 500;">
                                            <i class="bi bi-x-circle-fill me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-card-text me-1"></i>Descripción</div>
                                <div class="text-secondary">{{ $ruta->descripcion ?: 'Sin descripción registrada' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Paradas de la Ruta --}}
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3" style="color: var(--primary);">
                            <i class="bi bi-geo-alt-fill me-2"></i>Paradas del Recorrido ({{ $ruta->paradas->count() }})
                        </h6>

                        @if($ruta->paradas->count() > 0)
                            <div class="list-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                @foreach($ruta->paradas as $parada)
                                    <div class="list-group-item d-flex align-items-center justify-content-between p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge rounded-circle d-flex align-items-center justify-content-center"
                                                  style="width: 32px; height: 32px; background: var(--primary); color: white; font-size: 0.9rem;">
                                                {{ $parada->pivot->orden }}
                                            </span>
                                            <div>
                                                <div class="fw-semibold">{{ $parada->nombre }}</div>
                                                @if($parada->referencia)
                                                    <small class="text-muted"><i class="bi bi-signpost-2 me-1"></i>{{ $parada->referencia }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @if($parada->latitud && $parada->longitud)
                                                <small class="text-muted font-monospace">
                                                    <i class="bi bi-crosshair me-1"></i>{{ number_format($parada->latitud, 5) }}, {{ number_format($parada->longitud, 5) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light text-center py-4 border rounded-3 text-muted">
                                <i class="bi bi-geo-alt fs-3 d-block mb-2 text-secondary"></i>
                                No hay paradas asignadas a esta ruta.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                    <a href="{{ route('ruta.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
                        <i class="bi bi-arrow-left me-2"></i>Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
