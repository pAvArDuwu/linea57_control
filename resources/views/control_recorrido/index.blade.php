@extends('layouts.app')

@section('content')
@vite('resources/css/control-recorrido.css')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-pin-map-fill me-2" style="color: var(--accent);"></i>Control de Recorrido
            </h4>
            <p class="text-muted mb-0 small">Segunda transacción — Auditoría automática del cumplimiento de paradas por GPS</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('monitoreo.index') }}" class="btn btn-outline-primary px-3" style="border-radius: 10px;">
                <i class="bi bi-map me-1"></i>Seguimiento en Vivo
            </a>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('control-recorrido.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="form-control" style="border-radius: 10px;">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-semibold text-muted mb-1">Ruta</label>
                    <select name="ruta_id" class="form-select" style="border-radius: 10px;">
                        <option value="">Todas</option>
                        @foreach($rutas as $r)
                            <option value="{{ $r->id }}" {{ (string)$rutaId === (string)$r->id ? 'selected' : '' }}>{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Estado</label>
                    <select name="estado" class="form-select" style="border-radius: 10px;">
                        <option value="">Todos</option>
                        <option value="en_curso"   {{ $estado === 'en_curso'   ? 'selected' : '' }}>En curso</option>
                        <option value="pendiente"  {{ $estado === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                        <option value="completado" {{ $estado === 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="retrasado"  {{ $estado === 'retrasado'  ? 'selected' : '' }}>Retrasado</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px;">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    @if($rutaId || $estado || $fecha !== now()->toDateString())
                        <a href="{{ route('control-recorrido.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;" title="Limpiar">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Asignaciones --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-check fs-5"></i>
                <span class="fw-bold">Asignaciones de Turno ({{ $asignaciones->total() }} registros)</span>
            </div>
            <span class="badge bg-white bg-opacity-20 px-3 py-1 rounded-pill small">{{ $fecha }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-cr align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID Asignación</th>
                        <th>Fecha</th>
                        <th>Ruta</th>
                        <th>Turno</th>
                        <th>Micro</th>
                        <th>Conductor</th>
                        <th>Progreso</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $a)
                        <tr>
                            <td><span class="fw-bold text-primary">{{ $a->codigo }}</span></td>
                            <td><span class="text-muted small">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</span></td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">
                                    {{ $a->ruta->nombre ?? '—' }}
                                </span>
                            </td>
                            <td><span class="text-muted">{{ ucfirst($a->turno->nombre ?? '—') }}</span></td>
                            <td>
                                <span class="fw-semibold">{{ $a->micro->placa ?? 'S/P' }}</span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1 small">{{ $a->micro->interno->numero_interno ?? '' }}</span>
                            </td>
                            <td class="text-muted">{{ $a->conductor ? ($a->conductor->nombre . ' ' . $a->conductor->apellido) : 'Sin conductor' }}</td>
                            <td style="min-width: 130px;">
                                <div class="small text-muted mb-1">{{ $a->paradas_cumplidas }}/{{ $a->total_paradas }} paradas ({{ $a->porcentaje }}%)</div>
                                <div class="progress-mini"><div class="bar" style="width: {{ $a->porcentaje }}%;"></div></div>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-1 badge-{{ $a->estado }}" style="font-size: 0.78rem; font-weight: 600;">
                                    @if($a->estado === 'en_curso') 🟢 En ruta
                                    @elseif($a->estado === 'pendiente') 🟡 Pendiente
                                    @elseif($a->estado === 'completado') ✅ Finalizado
                                    @elseif($a->estado === 'retrasado') 🟠 Retrasado
                                    @else ⚪ {{ ucfirst($a->estado) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('control-recorrido.show', $a->id) }}"
                                       class="btn-action-cr d-inline-flex align-items-center gap-1" title="Ver recorrido">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('control-recorrido.mapa', $a->id) }}"
                                       class="btn-action-cr d-inline-flex align-items-center gap-1" title="Ubicar en mapa" style="color: var(--accent);">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </a>
                                    <a href="{{ route('control-recorrido.historial', $a->id) }}"
                                       class="btn-action-cr d-inline-flex align-items-center gap-1" title="Historial completo" style="color: #059669;">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-pin-map d-block fs-1 mb-2 opacity-30"></i>
                                No hay asignaciones para la fecha y filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($asignaciones->hasPages())
            <div class="card-footer py-3 px-4">
                {{ $asignaciones->appends(['fecha' => $fecha, 'ruta_id' => $rutaId, 'estado' => $estado])->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
