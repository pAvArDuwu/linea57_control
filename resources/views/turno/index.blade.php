@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-clock-fill me-2"></i>Catálogo de Turnos
            </h4>
            <p class="text-muted mb-0">Parámetros de horarios: Mañana, Tarde y Noche</p>
        </div>
        <a href="{{ route('turno.create') }}" class="btn px-4 py-2"
           style="border-radius: 12px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Turno
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
            <form method="GET" action="{{ route('turno.index') }}" class="d-flex gap-2" style="max-width: 420px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light"
                           placeholder="Buscar por nombre o descripción..." value="{{ $buscar ?? '' }}"
                           style="border-radius: 0 10px 10px 0;">
                </div>
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                @if(!empty($buscar))
                    <a href="{{ route('turno.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background: #f8f9fc;">
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">#</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Turno</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Hora Inicio</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Hora Fin</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Descripción</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem; text-transform: uppercase;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($turnos as $turno)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration + ($turnos->currentPage() - 1) * $turnos->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                             style="width: 38px; height: 38px; background: {{ $turno->turno_badge['bg'] }}; color: {{ $turno->turno_badge['color'] }}; font-size: 1.1rem;">
                                            <i class="bi {{ $turno->turno_badge['icono'] }}"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $turno->nombre_label }}</span>
                                    </div>
                                </td>
                                <td class="text-muted font-monospace">{{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }}</td>
                                <td class="text-muted font-monospace">{{ \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') }}</td>
                                <td class="text-muted">{{ $turno->descripcion ?? '—' }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2" style="background: {{ $turno->estado_badge['bg'] }}; color: {{ $turno->estado_badge['color'] }}; font-weight: 500;">
                                        {{ $turno->estado_badge['label'] }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('turno.show', $turno->id) }}"
                                           class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </a>
                                        <a href="{{ route('turno.edit', $turno->id) }}"
                                           class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.75rem;">
                                            <i class="bi bi-pencil-square me-1"></i>Editar
                                        </a>
                                        @if($turno->estado === 'activo')
                                            <form action="{{ route('turno.destroy', $turno->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('¿Eliminar el turno {{ $turno->nombre_label }}?')"
                                                        class="btn btn-sm btn-outline-danger"
                                                        style="border-radius: 8px; font-size: 0.75rem;">
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
                                    <i class="bi bi-clock d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                    No se encontraron turnos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($turnos->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                {{ $turnos->appends(['buscar' => $buscar])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
