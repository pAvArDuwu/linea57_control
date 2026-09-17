@extends('layouts.app')

@section('content')
<style>
    .cr-stat-card {
        border-radius: 14px;
        padding: 1.1rem 1.3rem;
        border: 1px solid rgba(0,0,0,0.06);
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        height: 100%;
    }
    .badge-en-curso   { background: #e3f2fd; color: #1565c0; }
    .badge-pendiente  { background: #fff9c4; color: #7a5c00; }
    .badge-completado { background: #e6f4ea; color: #1e7e34; }
    .badge-retrasado  { background: #fff3e0; color: #e65100; }
    .badge-cancelado  { background: #f0f0f0; color: #6c757d; }
    .table-cr thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        font-weight: 700;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
    }
    .table-cr tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        font-size: 0.92rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-cr tbody tr:hover td { background: #f8fafc; }
    .btn-action-cr {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 0.18s ease;
        border: none;
        background: none;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    .btn-action-cr:hover { background: #e8f0fe; color: var(--primary); }
    .progress-mini {
        height: 5px;
        border-radius: 10px;
        background: #e2e8f0;
        margin-top: 4px;
    }
    .progress-mini .bar {
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, #10b981, #3b82f6);
        transition: width 0.4s ease;
    }
    .info-box {
        background: #f8fafc;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 1.4rem;
        height: 100%;
    }
    .flow-step {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.6rem;
    }
    .flow-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

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

    {{-- Panel Informativo Inferior (3 tarjetas del mockup) --}}
    <div class="row g-4">
        {{-- Qué hace este formulario --}}
        <div class="col-12 col-lg-4">
            <div class="info-box">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:#e8f0fe;">
                        <i class="bi bi-info-circle-fill" style="color:var(--primary);"></i>
                    </div>
                    <h6 class="fw-bold mb-0" style="color:var(--primary);">¿Qué hace esta sección y por qué es necesaria?</h6>
                </div>
                <p class="text-muted small mb-2">Esta sección <strong>no registra manualmente</strong> cada parada. Su función es <strong>mostrar y consultar el control del recorrido</strong> que se genera automáticamente a partir de las posiciones GPS del micro.</p>
                <p class="text-muted small mb-0">Es necesaria porque permite al fiscalizador:</p>
                <ul class="text-muted small mt-2 mb-0">
                    <li>Ver en tiempo real la ubicación del micro y el progreso del recorrido.</li>
                    <li>Consultar el estado de cada parada (cumplida, omitida, pendiente o fuera de ruta).</li>
                    <li>Revisar la información histórica del recorrido (hora, distancia, observaciones).</li>
                    <li>Detectar incidencias y tomar decisiones oportunas (ej. desvíos, paradas omitidas).</li>
                </ul>
            </div>
        </div>

        {{-- Botones principales --}}
        <div class="col-12 col-lg-4">
            <div class="info-box">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:#fde8ec;">
                        <i class="bi bi-grid-1x2-fill" style="color:var(--accent);"></i>
                    </div>
                    <h6 class="fw-bold mb-0" style="color:var(--primary);">Botones principales</h6>
                </div>
                @php
                    $botonesInfo = [
                        ['icon' => 'bi-eye-fill',      'color' => 'var(--primary)',  'titulo' => 'Ver recorrido',     'desc' => 'Abre el mapa y el detalle del recorrido.'],
                        ['icon' => 'bi-geo-alt-fill',  'color' => 'var(--accent)',   'titulo' => 'Ubicar en mapa',    'desc' => 'Muestra la posición actual del micro en mapa ampliado.'],
                        ['icon' => 'bi-clock-history', 'color' => '#059669',         'titulo' => 'Historial completo','desc' => 'Muestra todas las paradas y sus estados con horarios.'],
                    ];
                @endphp
                @foreach($botonesInfo as $btn)
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:#f1f5f9;">
                            <i class="bi {{ $btn['icon'] }}" style="color:{{ $btn['color'] }};font-size:1rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small" style="color:{{ $btn['color'] }};">{{ $btn['titulo'] }}</div>
                            <div class="text-muted" style="font-size:0.8rem;">{{ $btn['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Flujo del proceso --}}
        <div class="col-12 col-lg-4">
            <div class="info-box">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:#e6f4ea;">
                        <i class="bi bi-arrow-right-circle-fill" style="color:#1e7e34;"></i>
                    </div>
                    <h6 class="fw-bold mb-0" style="color:var(--primary);">Flujo del proceso (resumen)</h6>
                </div>
                @php
                    $pasos = [
                        ['titulo' => 'Asignación de turno',   'desc' => 'Se registra el turno, ruta, micro y conductor.'],
                        ['titulo' => 'El micro inicia el turno', 'desc' => 'La app móvil envía posiciones GPS.'],
                        ['titulo' => 'Seguimiento GPS',       'desc' => 'Se guardan las posiciones en seguimiento_gps.'],
                        ['titulo' => 'Control de recorrido',  'desc' => 'Se evalúa cada posición respecto a la ruta y paradas.'],
                        ['titulo' => 'Resultado',             'desc' => 'Se actualiza el estado del recorrido y se muestra en la interfaz.'],
                    ];
                @endphp
                @foreach($pasos as $i => $paso)
                    <div class="flow-step">
                        <div class="flow-num">{{ $i + 1 }}</div>
                        <div>
                            <div class="fw-semibold small">{{ $paso['titulo'] }}</div>
                            <div class="text-muted" style="font-size:0.78rem;">{{ $paso['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
