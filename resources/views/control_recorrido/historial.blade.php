@extends('layouts.app')

@section('content')
<style>
    .hist-table thead th {
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        font-weight: 700;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        white-space: nowrap;
    }
    .hist-table tbody td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .hist-table tbody tr:hover td { background: #fafbfc; }
    .sentido-badge-ida    { background: #e8f0fe; color: #1565c0; }
    .sentido-badge-vuelta { background: #f3e5f5; color: #7b1fa2; }
    .estado-cumplido { background: #e6f4ea; color: #1e7e34; }
    .estado-omitido  { background: #fce8e6; color: #c62828; }
    .estado-pendiente{ background: #fff9c4; color: #7a5c00; }
    .meta-row { background: #f8fafc; border-radius: 10px; padding: 0.6rem 1rem; font-size: 0.85rem; }
</style>

<div class="container-fluid py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('control-recorrido.index') }}" class="text-decoration-none">Control de Recorrido</a></li>
            <li class="breadcrumb-item"><a href="{{ route('control-recorrido.show', $asignacion->id) }}" class="text-decoration-none">{{ $codigo }}</a></li>
            <li class="breadcrumb-item active">Historial</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('control-recorrido.show', $asignacion->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0" style="color:var(--primary);">Control de Recorrido — {{ $codigo }}</h4>
            </div>
            <p class="text-muted small mb-0">Lista de paradas en orden, con su estado, hora y observaciones.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('control-recorrido.mapa', $asignacion->id) }}" class="btn btn-sm px-3 text-white" style="border-radius:8px; background:var(--accent);">
                <i class="bi bi-fullscreen me-1"></i>Ver en mapa
            </a>
        </div>
    </div>

    {{-- Cabecera de resumen --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-12 col-md-3">
                    <div class="meta-row"><span class="text-muted small">Ruta:</span><br><strong>{{ $asignacion->ruta->nombre ?? '—' }}</strong></div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="meta-row"><span class="text-muted small">Micro:</span><br><strong>{{ $asignacion->micro->placa ?? 'S/P' }} — Int. {{ $asignacion->micro->interno->numero_interno ?? 'S/I' }}</strong></div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="meta-row"><span class="text-muted small">Conductor:</span><br><strong>{{ $asignacion->conductor?->nombre ?? '—' }} {{ $asignacion->conductor?->apellido ?? '' }}</strong></div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="meta-row"><span class="text-muted small">Fecha:</span><br><strong>{{ \Carbon\Carbon::parse($asignacion->fecha)->format('d/m/Y') }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla principal --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-ol"></i>
                <span class="fw-bold">Secuencia de Paradas — {{ $filas->count() }} total</span>
            </div>
            <div class="d-flex gap-3 small">
                <span><span class="badge estado-cumplido px-2">Cumplido</span></span>
                <span><span class="badge estado-omitido px-2">Omitido</span></span>
                <span><span class="badge estado-pendiente px-2">Pendiente</span></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table hist-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Parada</th>
                        <th>Sentido</th>
                        <th>Hora prevista</th>
                        <th>Hora paso</th>
                        <th>Estado</th>
                        <th>Distancia (m)</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filas as $fila)
                        <tr>
                            <td>
                                <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold"
                                      style="width:28px;height:28px;background:{{ $fila['sentido'] === 'Ida' ? '#1565c0' : '#7b1fa2' }};font-size:0.75rem;">
                                    {{ $fila['numero'] }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $fila['parada']?->nombre ?? '—' }}</div>
                                @if($fila['parada']?->referencia)
                                    <small class="text-muted">{{ $fila['parada']->referencia }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill px-2 py-1 {{ $fila['sentido'] === 'Ida' ? 'sentido-badge-ida' : 'sentido-badge-vuelta' }}" style="font-size:0.75rem;">
                                    {{ $fila['sentido'] === 'Ida' ? '→ Ida' : '← Vuelta' }}
                                </span>
                            </td>
                            <td class="font-monospace text-muted">{{ $fila['hora_prevista'] ?? '—' }}</td>
                            <td class="font-monospace {{ $fila['hora_paso'] ? 'text-success fw-semibold' : 'text-muted' }}">{{ $fila['hora_paso'] ?? '— : —' }}</td>
                            <td>
                                @php
                                    $estadoCls = match($fila['estado']) {
                                        'cumplido'  => 'estado-cumplido',
                                        'omitido'   => 'estado-omitido',
                                        default     => 'estado-pendiente',
                                    };
                                    $estadoLabel = match($fila['estado']) {
                                        'cumplido'  => '✓ Cumplido',
                                        'omitido'   => '✗ Omitido',
                                        'fuera_ruta'=> '⚠ Fuera de ruta',
                                        default     => '⏳ Pendiente',
                                    };
                                @endphp
                                <span class="badge rounded-pill px-2 py-1 {{ $estadoCls }}" style="font-size:0.75rem;">{{ $estadoLabel }}</span>
                            </td>
                            <td class="text-muted font-monospace">{{ $fila['distancia'] !== null ? number_format($fila['distancia'], 1) : '—' }}</td>
                            <td class="text-muted" style="max-width:200px; font-size:0.8rem;">{{ $fila['observacion'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-list-ol d-block fs-1 mb-2 opacity-30"></i>
                                Esta ruta no tiene paradas configuradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="text-muted small">Mostrando {{ $filas->count() }} de {{ $filas->count() }} registros</span>
        </div>
    </div>

</div>
@endsection
