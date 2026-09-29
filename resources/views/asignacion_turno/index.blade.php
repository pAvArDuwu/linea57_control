@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-calendar2-check-fill me-2" style="color: var(--accent);"></i>Asignación de Turnos
            </h4>
            <p class="text-muted mb-0 small">Gestión y programación de turnos asignados a conductores, micros y rutas</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('asignacion-turno.create') }}" class="btn px-4 py-2"
               style="border-radius: 12px; background: linear-gradient(135deg, var(--accent) 0%, #a02035 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(123,30,43,0.25);">
                <i class="bi bi-plus-lg me-2"></i>Nueva Asignación
            </a>
        </div>
    </div>

    <!-- Alertas Flash -->
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

    <!-- Filtros de Búsqueda y Fecha -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('asignacion-turno.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="form-control" style="border-radius: 10px;" onchange="this.form.submit()">
                </div>
                <div class="col-12 col-md-7">
                    <label class="form-label small fw-semibold text-muted mb-1">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="buscar" class="form-control border-start-0" placeholder="Buscar por conductor, código o placa..." value="{{ $buscar ?? '' }}" style="border-radius: 0 10px 10px 0;">
                    </div>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px;">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    @if($buscar || $fecha !== now()->toDateString())
                        <a href="{{ route('asignacion-turno.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;" title="Limpiar Filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Asignaciones de Turno -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-radius: 16px 16px 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-check text-primary fs-5"></i>
                <span class="fw-bold text-dark">Tabla de Asignaciones de Turnos ({{ $asignaciones->total() }} registros)</span>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.78rem;">
                Fecha: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem;">Código</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem;">Fecha</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem;">Turno</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem;">Conductor</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem;">Unidad / Micro</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem;">Ruta</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignaciones as $a)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">{{ $a->codigo }}</td>
                                <td class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1" style="background: #e3f2fd; color: #0B3C78; font-weight: 500;">
                                        {{ $a->turno_emoji }} {{ $a->turno?->nombre_label ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    @if($a->conductor)
                                        <div class="fw-semibold text-dark small">{{ $a->conductor->nombre }} {{ $a->conductor->apellido }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Lic: {{ $a->conductor->licencia ?? 'S/L' }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small"><i class="bi bi-bus-front me-1 text-primary"></i>{{ $a->micro->placa ?? 'S/P' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">Int. {{ $a->micro->interno->numero_interno ?? 'S/I' }} · {{ $a->micro->modelo ?? '' }}</div>
                                </td>
                                <td class="text-muted small">
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $a->ruta?->nombre ?? '—' }}</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1" style="background: {{ $a->estado_badge['bg'] }}; color: {{ $a->estado_badge['color'] }}; font-weight: 600;">
                                        {{ $a->estado_badge['label'] }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('asignacion-turno.show', $a->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('asignacion-turno.edit', $a->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if($a->estado !== 'cancelado')
                                            <form action="{{ route('asignacion-turno.destroy', $a->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Cancelar esta asignación?')" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Cancelar Asignación">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-30"></i>
                                    No se encontraron asignaciones para la fecha o búsqueda seleccionada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($asignaciones->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                {{ $asignaciones->appends(['buscar' => $buscar, 'fecha' => $fecha])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
