@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-signpost-2-fill me-2"></i>Gestión de Rutas
            </h4>
            <p class="text-muted mb-0">Administra las rutas del sistema de transporte</p>
        </div>
        <a href="{{ route('ruta.create') }}" class="btn btn-primary-custom px-4 py-2">
            <i class="bi bi-plus-lg me-2"></i>Nueva Ruta
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert"
             style="border-radius: 12px; border-left: 4px solid #198754 !important;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h6 class="mb-0 fw-semibold text-dark">
                    <i class="bi bi-list-ul me-2 text-muted"></i>Lista de Rutas
                </h6>
                <form method="GET" action="{{ route('ruta.index') }}" class="d-flex gap-2" style="max-width: 400px; width: 100%;">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0 bg-light"
                               placeholder="Buscar rutas..." value="{{ $buscar ?? '' }}"
                               style="border-radius: 0 10px 10px 0;">
                    </div>
                    <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                    @if(!empty($buscar))
                        <a href="{{ route('ruta.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background: #f8f9fc;">
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.85rem;">No</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Nombre</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Descripción</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Estado</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Paradas (Ida + Vuelta)</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.85rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rutas as $ruta)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration + ($rutas->currentPage() - 1) * $rutas->perPage() }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                                             style="width: 36px; height: 36px; background: linear-gradient(135deg, #fde8ec 0%, #f8c4ce 100%); flex-shrink: 0;">
                                            <i class="bi bi-signpost-2" style="color: var(--accent); font-size: 0.9rem;"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $ruta->nombre }}</span>
                                    </div>
                                </td>
                                <td class="text-muted" style="max-width: 250px;">
                                    {{ Str::limit($ruta->descripcion ?? '—', 60) }}
                                </td>
                                <td>
                                    @if($ruta->estado === 'activo')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34; font-weight: 500;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #fce8e6; color: #c62828; font-weight: 500;">
                                            <i class="bi bi-x-circle-fill me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2" style="background: #fff3e0; color: #e65100; font-weight: 500;">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $ruta->paradas_count }} paradas
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('ruta.show', $ruta->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </a>
                                        <a href="{{ route('ruta.edit', $ruta->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-pencil-square me-1"></i>Editar
                                        </a>
                                        @if($ruta->estado === 'activo')
                                            <form action="{{ route('ruta.destroy', $ruta->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar esta ruta?')"
                                                        class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-size: 0.75rem;">
                                                    <i class="bi bi-trash me-1"></i>Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                                             style="width: 64px; height: 64px; background: #f0f4f8;">
                                            <i class="bi bi-signpost-2 text-muted" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <p class="text-muted mb-1">No se encontraron rutas</p>
                                        <a href="{{ route('ruta.create') }}" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="bi bi-plus-lg me-1"></i>Crear primera ruta
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($rutas->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                {{ $rutas->appends(['buscar' => $buscar])->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(11, 60, 120, 0.25);
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(11, 60, 120, 0.35);
        color: white;
    }
    .btn-action {
        border-radius: 8px;
        padding: 0.35rem 0.55rem;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-1px);
    }
    .table > tbody > tr {
        transition: background-color 0.15s ease;
    }
    .table > tbody > tr:hover {
        background-color: #f8f9fc !important;
    }
</style>
@endsection
