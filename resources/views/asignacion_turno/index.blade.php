@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-calendar2-check-fill me-2"></i>Asignación de Turnos
            </h4>
            <p class="text-muted mb-0">Operaciones diarias: asignación de conductor, micro e interno a un turno y ruta</p>
        </div>
        <a href="{{ route('asignacion-turno.create') }}" class="btn px-4 py-2"
           style="border-radius: 12px; background: linear-gradient(135deg, var(--accent) 0%, #a02035 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(123,30,43,0.25);">
            <i class="bi bi-plus-lg me-2"></i>Nueva Asignación
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
            <form method="GET" action="{{ route('asignacion-turno.index') }}" class="d-flex gap-2" style="max-width: 420px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light"
                           placeholder="Buscar por conductor o fecha..." value="{{ $buscar ?? '' }}"
                           style="border-radius: 0 10px 10px 0;">
                </div>
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                @if(!empty($buscar))
                    <a href="{{ route('asignacion-turno.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
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
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Fecha</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Turno</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Conductor</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Interno / Micro</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Ruta</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem; text-transform: uppercase;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignaciones as $a)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration + ($asignaciones->currentPage() - 1) * $asignaciones->perPage() }}</td>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $iconos = ['mañana' => '☀️', 'tarde' => '🌤️', 'noche' => '🌙'];
                                    @endphp
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background: #e3f2fd; color: #0B3C78; font-weight: 500;">
                                        {{ $iconos[$a->turno?->nombre ?? ''] ?? '' }}
                                        {{ $a->turno?->nombre_label ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    @if($a->conductor)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                                 style="width: 32px; height: 32px; background: linear-gradient(135deg,#fde8ec,#f8c4ce); color: var(--accent); font-weight: 700; font-size: 0.75rem;">
                                                {{ strtoupper(substr($a->conductor->nombre, 0, 1)) }}{{ strtoupper(substr($a->conductor->apellido, 0, 1)) }}
                                            </div>
                                            <span class="fw-semibold small">{{ $a->conductor->nombre }} {{ $a->conductor->apellido }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    @if($a->interno)
                                        <div><i class="bi bi-hdd-stack me-1"></i>Int. {{ $a->interno->numero_interno }}</div>
                                    @endif
                                    @if($a->micro)
                                        <div><i class="bi bi-bus-front me-1"></i>{{ $a->micro->placa }}</div>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $a->ruta?->nombre ?? '—' }}</td>
                                <td>
                                    @php
                                        $estadoBadge = [
                                            'pendiente'  => ['bg' => '#fff9c4', 'color' => '#795548'],
                                            'en_curso'   => ['bg' => '#e3f2fd', 'color' => '#1565c0'],
                                            'completado' => ['bg' => '#e6f4ea', 'color' => '#1e7e34'],
                                            'retrasado'  => ['bg' => '#fff3e0', 'color' => '#e65100'],
                                            'cancelado'  => ['bg' => '#f0f0f0', 'color' => '#6c757d'],
                                        ];
                                        $badge = $estadoBadge[$a->estado] ?? ['bg' => '#f0f0f0', 'color' => '#6c757d'];
                                    @endphp
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; font-weight: 500;">
                                        {{ ucfirst(str_replace('_', ' ', $a->estado)) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('asignacion-turno.show', $a->id) }}"
                                           class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.8rem;">Ver</a>
                                        <a href="{{ route('asignacion-turno.edit', $a->id) }}"
                                           class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.8rem;">Editar</a>
                                        @if($a->estado !== 'cancelado')
                                            <form action="{{ route('asignacion-turno.destroy', $a->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('¿Cancelar esta asignación?')"
                                                        class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; font-size: 0.8rem;">Cancelar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar2-x d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                    No se encontraron asignaciones de turno.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($asignaciones->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                {{ $asignaciones->appends(['buscar' => $buscar])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
