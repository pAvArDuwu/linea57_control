@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);"><i class="bi bi-person-badge-fill me-2"></i>Gestión de Conductores</h4>
            <p class="text-muted mb-0">Administra los conductores del sistema</p>
        </div>
        <a href="{{ route('conductor.create') }}" class="btn px-4 py-2"
           style="border-radius: 12px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Conductor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
            <form method="GET" action="{{ route('conductor.index') }}" class="d-flex gap-2" style="max-width: 420px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light" placeholder="Buscar por nombre, CI, correo..." value="{{ $buscar ?? '' }}" style="border-radius: 0 10px 10px 0;">
                </div>
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                @if(!empty($buscar))<a href="{{ route('conductor.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;"><i class="bi bi-x-lg"></i></a>@endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background: #f8f9fc;">
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">#</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Conductor</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">CI</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Teléfono</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Correo</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem; text-transform: uppercase;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conductores as $conductor)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration + ($conductores->currentPage() - 1) * $conductores->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                             style="width: 38px; height: 38px; background: linear-gradient(135deg, #fde8ec 0%, #f8c4ce 100%); color: var(--accent); font-weight: 700; font-size: 0.85rem;">
                                            {{ strtoupper(substr($conductor->nombre, 0, 1)) }}{{ strtoupper(substr($conductor->apellido, 0, 1)) }}
                                        </div>
                                        <div class="fw-semibold">{{ $conductor->nombre }} {{ $conductor->apellido }}</div>
                                    </div>
                                </td>
                                <td class="text-muted font-monospace">{{ $conductor->ci }}</td>
                                <td class="text-muted">{{ $conductor->telefono }}</td>
                                <td class="text-muted">{{ $conductor->correo }}</td>
                                <td>
                                    @if(($conductor->estado ?? 'activo') === 'activo')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34; font-weight: 500;">Activo</span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #f0f0f0; color: #6c757d; font-weight: 500;">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('conductor.show', $conductor->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.8rem;">Ver</a>
                                        <a href="{{ route('conductor.edit', $conductor->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.8rem;">Editar</a>
                                        @if(($conductor->estado ?? 'activo') === 'activo')
                                            <form action="{{ route('conductor.destroy', $conductor->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Desactivar este conductor?')"
                                                        class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; font-size: 0.8rem;">Desactivar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-person-badge d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>No se encontraron conductores.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($conductores->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">{{ $conductores->appends(['buscar' => $buscar])->links() }}</div>
        @endif
    </div>
</div>
@endsection