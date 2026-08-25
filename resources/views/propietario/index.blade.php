@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-person-vcard-fill me-2"></i>Gestión de Propietarios
            </h4>
            <p class="text-muted mb-0">Administra los propietarios del sistema</p>
        </div>
        <a href="{{ route('propietario.create') }}" class="btn px-4 py-2"
           style="border-radius: 12px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Propietario
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4"
             style="border-radius: 12px; border-left: 4px solid #198754 !important;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
            <form method="GET" action="{{ route('propietario.index') }}" class="d-flex gap-2" style="max-width: 420px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light"
                           placeholder="Buscar por nombre, CI, correo..." value="{{ $buscar ?? '' }}"
                           style="border-radius: 0 10px 10px 0;">
                </div>
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                @if(!empty($buscar))
                    <a href="{{ route('propietario.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background: #f8f9fc;">
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">Propietario</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">CI</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">Teléfono</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">Correo</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propietarios as $propietario)
                            <tr>
                                <td class="ps-4 text-muted" style="font-size: 0.88rem;">{{ $loop->iteration + ($propietarios->currentPage() - 1) * $propietarios->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                             style="width: 38px; height: 38px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%); color: var(--primary); font-weight: 700; font-size: 0.85rem;">
                                            {{ strtoupper(substr($propietario->nombre, 0, 1)) }}{{ strtoupper(substr($propietario->apellido, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $propietario->nombre }} {{ $propietario->apellido }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted font-monospace">{{ $propietario->ci }}</td>
                                <td class="text-muted">{{ $propietario->telefono ?? '—' }}</td>
                                <td class="text-muted">{{ $propietario->correo }}</td>
                                <td>
                                    @if(($propietario->estado ?? 'activo') === 'activo')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34; font-weight: 500;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #f0f0f0; color: #6c757d; font-weight: 500;">
                                            <i class="bi bi-dash-circle-fill me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1 flex-wrap">
                                        <a href="{{ route('propietario.show', $propietario->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </a>
                                        <a href="{{ route('propietario.edit', $propietario->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-pencil-square me-1"></i>Editar
                                        </a>
                                        @if(($propietario->estado ?? 'activo') === 'activo')
                                            <form action="{{ route('propietario.destroy', $propietario->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar este propietario?')"
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
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-vcard d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                    No se encontraron propietarios.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($propietarios->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                {{ $propietarios->appends(['buscar' => $buscar])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
