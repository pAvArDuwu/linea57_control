@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);"><i class="bi bi-bus-front-fill me-2"></i>Gestión de Micros</h4>
            <p class="text-muted mb-0">Administra los vehículos del sistema</p>
        </div>
        <a href="{{ route('micro.create') }}" class="btn px-4 py-2"
           style="border-radius: 12px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Micro
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
            <form method="GET" action="{{ route('micro.index') }}" class="d-flex gap-2" style="max-width: 420px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light" placeholder="Buscar por placa, modelo, marca..." value="{{ $buscar ?? '' }}" style="border-radius: 0 10px 10px 0;">
                </div>
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                @if(!empty($buscar))<a href="{{ route('micro.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;"><i class="bi bi-x-lg"></i></a>@endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background: #f8f9fc;">
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">#</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Placa</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Vehículo</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Propietario</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Interno</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Capacidad</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem; text-transform: uppercase;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($micros as $micro)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration + ($micros->currentPage() - 1) * $micros->perPage() }}</td>
                                <td><span class="badge fw-bold px-3 py-2" style="background: #fff3e0; color: #e65100; font-size: 0.85rem; border-radius: 8px;">{{ $micro->placa }}</span></td>
                                <td>
                                    <div class="fw-semibold">{{ $micro->marca }} {{ $micro->modelo }}</div>
                                    <small class="text-muted">{{ $micro->anio_fabricacion ?? 'Sin año' }}</small>
                                </td>
                                <td class="text-muted">
                                    @if($micro->propietario)
                                        {{ $micro->propietario->nombre }} {{ $micro->propietario->apellido }}
                                    @else
                                        <span class="text-danger">—</span>
                                    @endif
                                </td>
                                <td class="text-muted font-monospace">{{ $micro->interno->numero_interno ?? '—' }}</td>
                                <td class="text-muted">{{ $micro->capacidad_pasajeros }} pax</td>
                                <td>
                                    @if($micro->estado === 'activo')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34; font-weight: 500;">Activo</span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #f0f0f0; color: #6c757d; font-weight: 500;">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('micro.show', $micro->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.8rem;">Ver</a>
                                        <a href="{{ route('micro.edit', $micro->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.8rem;">Editar</a>
                                        @if($micro->estado === 'activo')
                                            <form action="{{ route('micro.destroy', $micro->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Desactivar este micro?')"
                                                        class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; font-size: 0.8rem;">Desactivar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-bus-front d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>No se encontraron micros.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($micros->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">{{ $micros->appends(['buscar' => $buscar])->links() }}</div>
        @endif
    </div>
</div>
@endsection
