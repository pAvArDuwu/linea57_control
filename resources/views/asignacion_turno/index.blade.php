@extends('layouts.app')
@section('content')
<div class="container-fluid py-4" id="tableroApp">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-calendar2-check-fill me-2"></i>Tablero de Asignación de Turnos
            </h4>
            <p class="text-muted mb-0">Control operativo en tiempo real por unidad, conductor y ruta</p>
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

    <!-- 1. Tarjetas de Estadísticas (SDD Sección 30.1) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 stat-card" style="border-radius: 14px; border-left: 4px solid var(--primary);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Turnos Cubiertos</div>
                        <div class="fs-3 fw-bold mt-1 text-dark">{{ $stats['turnos_cubiertos'] }}</div>
                        <span class="badge bg-light text-primary small mt-1">Hoy: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e3f2fd; color: var(--primary);">
                        <i class="bi bi-calendar2-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 14px; border-left: 4px solid #2e7d32;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Conductores Libres</div>
                        <div class="fs-3 fw-bold mt-1 text-dark">{{ $stats['conductores_libres'] }}</div>
                        <span class="badge bg-light text-success small mt-1">Disponibles</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e8f5e9; color: #2e7d32;">
                        <i class="bi bi-person-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 14px; border-left: 4px solid #0288d1;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Unidades en Ruta</div>
                        <div class="fs-3 fw-bold mt-1 text-dark">{{ $stats['unidades_en_ruta'] }}</div>
                        <span class="badge bg-light text-info small mt-1">Transmitiendo GPS</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e1f5fe; color: #0288d1;">
                        <i class="bi bi-bus-front fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 2. Tablero Visual por Unidad (Timeline/Gantt) y Panel Lateral -->
    <div class="row g-4 mb-4">
        <!-- Tablero por Unidad -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-layout-three-columns text-primary fs-5"></i>
                        <span class="fw-bold text-dark">Programación de Unidades del Día</span>
                    </div>
                    <form method="GET" action="{{ route('asignacion-turno.index') }}" class="d-flex align-items-center gap-2">
                        <input type="date" name="fecha" value="{{ $fecha }}" class="form-control form-control-sm" style="border-radius: 8px;" onchange="this.form.submit()">
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 text-center">
                            <thead style="background: #f8f9fc;">
                                <tr>
                                    <th class="py-3 text-start ps-3 text-muted fw-semibold" style="width: 28%; font-size: 0.82rem;">Unidad / Micro</th>
                                    @foreach($turnosCatalogo as $t)
                                        <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem;">
                                            <div class="text-capitalize">{{ $t->nombre }}</div>
                                            <div class="text-muted fw-normal" style="font-size: 0.72rem;">{{ substr($t->hora_inicio, 0, 5) }} - {{ substr($t->hora_fin, 0, 5) }}</div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unidadesTimeline as $u)
                                    <tr>
                                        <td class="text-start ps-3 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary"
                                                     style="width: 36px; height: 36px; background: #e3f2fd; font-weight: 700; font-size: 0.8rem;">
                                                    {{ $u['micro']->interno->numero_interno ?? 'M' }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $u['micro']->placa }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">Int. {{ $u['micro']->interno->numero_interno ?? 'S/I' }} · {{ $u['micro']->modelo }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($turnosCatalogo as $t)
                                            @php
                                                $asig = $u['turnos'][$t->nombre] ?? null;
                                            @endphp
                                            <td class="p-2">
                                                @if($asig)
                                                    <div class="p-2 rounded-3 text-start border shadow-sm cursor-pointer turno-segmento"
                                                         onclick="mostrarDetalleTurno({{ json_encode([
                                                            'id' => $asig->id,
                                                            'fecha' => \Carbon\Carbon::parse($asig->fecha)->format('d/m/Y'),
                                                            'conductor' => $asig->conductor ? ($asig->conductor->nombre . ' ' . $asig->conductor->apellido) : 'Sin conductor',
                                                            'licencia' => $asig->conductor->licencia ?? 'S/L',
                                                            'placa' => $asig->micro->placa ?? '',
                                                            'interno' => $asig->micro->interno->numero_interno ?? '',
                                                            'ruta' => $asig->ruta->nombre ?? 'Sin ruta',
                                                            'turno' => ucfirst($asig->turno->nombre ?? ''),
                                                            'horario' => ($asig->turno->hora_inicio ?? '') . ' - ' . ($asig->turno->hora_fin ?? ''),
                                                            'estado' => $asig->estado,
                                                            'estado_label' => $asig->estado_badge['label'],
                                                            'estado_bg' => $asig->estado_badge['bg'],
                                                            'estado_color' => $asig->estado_badge['color'],
                                                            'hora_salida' => $asig->hora_salida ?? 'Pendiente',
                                                            'hora_llegada' => $asig->hora_llegada ?? 'Pendiente',
                                                            'observaciones' => $asig->observaciones ?? 'Sin observaciones',
                                                            'show_url' => route('asignacion-turno.show', $asig->id),
                                                            'edit_url' => route('asignacion-turno.edit', $asig->id),
                                                         ]) }})"
                                                         style="background: {{ $asig->estado_badge['bg'] }}; border-color: {{ $asig->estado_badge['color'] }}20 !important; cursor: pointer;">
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <span class="badge rounded-pill" style="background: {{ $asig->estado_badge['color'] }}; color: white; font-size: 0.65rem;">
                                                                {{ $asig->estado_badge['label'] }}
                                                            </span>
                                                        </div>
                                                        <div class="fw-bold text-truncate" style="font-size: 0.78rem; color: #1a202c;">
                                                            <i class="bi bi-person-fill me-1"></i>{{ $asig->conductor ? $asig->conductor->nombre : 'Sin conductor' }}
                                                        </div>
                                                        <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                                                            <i class="bi bi-signpost-2 me-1"></i>{{ $asig->ruta->nombre ?? '' }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="{{ route('asignacion-turno.create') }}?micro_id={{ $u['micro']->id }}&turno_id={{ $t->id }}&fecha={{ $fecha }}"
                                                       class="d-flex align-items-center justify-content-center p-2 rounded-3 text-muted text-decoration-none border border-dashed hover-bg"
                                                       style="background: #fafbfc; min-height: 52px; font-size: 0.75rem;">
                                                        <i class="bi bi-plus-circle me-1"></i>Libre
                                                    </a>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($turnosCatalogo) + 1 }}" class="py-4 text-muted text-center">
                                            No hay unidades activas registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral de Detalle Interactivo -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4" id="panelDetalleTurno" style="border-radius: 16px; border: 1px solid #dfeafc; background: #ffffff;">
                <div class="card-header border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0; background: linear-gradient(135deg, #eaf3ff 0%, #ffffff 100%); border-color: #dfeafc;">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold text-primary"><i class="bi bi-info-circle-fill me-2"></i>Detalle de Asignación</span>
                        <span id="detalleBadge" class="badge rounded-pill px-3 py-1 bg-light text-primary border">Seleccione un turno</span>
                    </div>
                </div>
                <div class="card-body p-4" id="detalleContenido">
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-hand-index-thumb fs-1 d-block mb-2 text-primary opacity-50"></i>
                        <p class="small mb-0">Haz clic en cualquier turno del tablero para ver todos sus detalles operativos en vivo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Tabla Completa de Asignaciones (Historial y Búsqueda) -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-radius: 16px 16px 0 0;">
            <div class="fw-bold text-dark"><i class="bi bi-list-check text-primary me-2"></i>Historial de Asignaciones</div>
            <form method="GET" action="{{ route('asignacion-turno.index') }}" class="d-flex gap-2" style="max-width: 380px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light" placeholder="Buscar conductor o fecha..." value="{{ $buscar ?? '' }}">
                </div>
                <button type="submit" class="btn btn-sm btn-outline-primary">Buscar</button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem;">#</th>
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
                                <td class="ps-4 text-muted">{{ $loop->iteration + ($asignaciones->currentPage() - 1) * $asignaciones->perPage() }}</td>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1" style="background: #e3f2fd; color: #0B3C78; font-weight: 500;">
                                        {{ $a->turno_emoji }} {{ $a->turno?->nombre_label ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    @if($a->conductor)
                                        <span class="fw-semibold small">{{ $a->conductor->nombre }} {{ $a->conductor->apellido }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    <i class="bi bi-bus-front me-1"></i>{{ $a->micro->placa ?? '' }} (Int. {{ $a->micro->interno->numero_interno ?? 'S/I' }})
                                </td>
                                <td class="text-muted small">{{ $a->ruta?->nombre ?? '—' }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1" style="background: {{ $a->estado_badge['bg'] }}; color: {{ $a->estado_badge['color'] }}; font-weight: 600;">
                                        {{ $a->estado_badge['label'] }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('asignacion-turno.show', $a->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('asignacion-turno.edit', $a->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px;">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if($a->estado !== 'cancelado')
                                            <form action="{{ route('asignacion-turno.destroy', $a->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Cancelar esta asignación?')" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No se encontraron asignaciones.</td>
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

<script>
function mostrarDetalleTurno(data) {
    const badge = document.getElementById('detalleBadge');
    const contenido = document.getElementById('detalleContenido');

    badge.className = 'badge rounded-pill px-3 py-1';
    badge.style.backgroundColor = data.estado_bg;
    badge.style.color = data.estado_color;
    badge.innerText = data.estado_label;

    contenido.innerHTML = `
        <div class="mb-3">
            <h5 class="fw-bold text-dark mb-0">${data.placa} <span class="text-muted fs-6">(Int. ${data.interno})</span></h5>
            <span class="small text-muted">${data.fecha} · Turno ${data.turno}</span>
        </div>
        <hr class="my-2 text-muted opacity-25">
        <div class="mb-2">
            <span class="text-muted small d-block">Conductor Asignado:</span>
            <span class="fw-semibold text-dark"><i class="bi bi-person-fill me-1 text-primary"></i>${data.conductor}</span>
            <span class="badge bg-light text-muted ms-1">Lic. ${data.licencia}</span>
        </div>
        <div class="mb-2">
            <span class="text-muted small d-block">Ruta:</span>
            <span class="fw-semibold text-dark"><i class="bi bi-signpost-2-fill me-1 text-primary"></i>${data.ruta}</span>
        </div>
        <div class="row g-2 my-2">
            <div class="col-6">
                <div class="p-2 rounded-2 bg-light">
                    <span class="text-muted d-block" style="font-size: 0.7rem;">Hora Salida</span>
                    <span class="fw-bold text-dark small">${data.hora_salida}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 rounded-2 bg-light">
                    <span class="text-muted d-block" style="font-size: 0.7rem;">Hora Llegada</span>
                    <span class="fw-bold text-dark small">${data.hora_llegada}</span>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <span class="text-muted small d-block">Observaciones:</span>
            <span class="small text-dark">${data.observaciones}</span>
        </div>
        <div class="d-flex gap-2 mt-3">
            <a href="${data.show_url}" class="btn btn-sm btn-primary flex-fill" style="border-radius: 8px;">Ver Detalle</a>
        </div>
    `;
}
</script>
@endsection
