@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);"><i class="bi bi-hdd-stack-fill me-2"></i>Gestión de Internos</h4>
            <p class="text-muted mb-0">Administra los números internos de los vehículos</p>
        </div>
        <a href="{{ route('interno.create') }}" class="btn px-4 py-2"
           style="border-radius: 12px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Interno
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
            <form method="GET" action="{{ route('interno.index') }}" class="d-flex gap-2" style="max-width: 420px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light" placeholder="Buscar por número interno..." value="{{ $buscar ?? '' }}" style="border-radius: 0 10px 10px 0;">
                </div>
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                @if(!empty($buscar))<a href="{{ route('interno.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;"><i class="bi bi-x-lg"></i></a>@endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background: #f8f9fc;">
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">#</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">N° Interno</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Fecha Ingreso</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Observaciones</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem; text-transform: uppercase;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($internos as $interno)
                            <tr>
                                <td class="ps-4 text-muted" style="font-size: 0.88rem;">{{ $loop->iteration + ($internos->currentPage() - 1) * $internos->perPage() }}</td>
                                <td><span class="fw-bold font-monospace fs-6">{{ $interno->numero_interno }}</span></td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($interno->fecha_ingreso)->format('d/m/Y') }}</td>
                                <td class="text-muted" style="max-width: 250px;">{{ Str::limit($interno->observaciones ?? '—', 50) }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2" style="background: {{ $interno->estado_badge['bg'] }}; color: {{ $interno->estado_badge['color'] }}; font-weight: 500;">
                                        {{ $interno->estado_badge['label'] }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('interno.show', $interno->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </a>
                                        <a href="{{ route('interno.edit', $interno->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-pencil-square me-1"></i>Editar
                                        </a>
                                        @if($interno->estado !== 'inactivo')
                                            <form action="{{ route('interno.destroy', $interno->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar este interno?')"
                                                        class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-size: 0.75rem;">
                                                    <i class="bi bi-trash me-1"></i>Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-hdd-stack d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>No se encontraron internos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($internos->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">{{ $internos->appends(['buscar' => $buscar])->links() }}</div>
        @endif
    </div>
</div>
@endsection
