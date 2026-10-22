@extends('layouts.app')
@section('content')
@vite('resources/css/control-paradas.css')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 control-paradas-title">
                <i class="bi bi-pin-map-fill me-2 control-paradas-title-icon"></i>Control de Paradas
            </h4>
            <p class="text-muted mb-0">Auditoría y verificación automática del paso de unidades por cada parada de la ruta</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('control-recorrido.index') }}" class="btn btn-primary px-3 py-2 control-paradas-action">
                <i class="bi bi-signpost-split me-1"></i>Ver Control de Recorrido
            </a>
            <a href="{{ route('monitoreo.index') }}" class="btn btn-outline-primary px-3 py-2 control-paradas-action">
                <i class="bi bi-map me-1"></i>Ver Seguimiento en Mapa
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4 control-paradas-filter-card">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('control-paradas.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-semibold text-muted mb-1">Fecha de Operación</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="form-control control-paradas-control">
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label small fw-semibold text-muted mb-1">Filtrar por Ruta</label>
                    <select name="ruta_id" class="form-select control-paradas-control">
                        <option value="">— Todas las rutas —</option>
                        @foreach($rutas as $r)
                            <option value="{{ $r->id }}" {{ (string)$rutaId === (string)$r->id ? 'selected' : '' }}>
                                {{ $r->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 control-paradas-filter-button">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    @if($rutaId || $fecha !== now()->toDateString())
                        <a href="{{ route('control-paradas.index') }}" class="btn btn-outline-secondary control-paradas-filter-button">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Unidades y Cumplimiento de Paradas -->
    <div class="row g-4">
        @forelse($asignaciones as $a)
            @php
                $paradasRuta = $a->ruta?->paradas ?? collect();
                $controlesCumplidos = $a->controlesRecorrido->where('estado', 'cumplido');
                $totalParadas = $paradasRuta->count();
                $totalCumplidas = $controlesCumplidos->count();
                $porcentaje = $totalParadas > 0 ? round(($totalCumplidas / $totalParadas) * 100) : 0;
            @endphp
            <div class="col-12">
                <div class="card border-0 shadow-sm control-paradas-assignment-card">
                    <div class="card-header bg-white border-bottom py-3 px-4 control-paradas-header">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-3">
                                  <div class="rounded-circle d-flex align-items-center justify-content-center text-primary control-paradas-unit-avatar">
                                    <i class="bi bi-bus-front"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">
                                        {{ $a->micro->placa ?? 'S/P' }}
                                        <span class="badge bg-light text-primary border ms-1">Int. {{ $a->micro->interno->numero_interno ?? 'S/I' }}</span>
                                    </h6>
                                    <div class="small text-muted">
                                        <i class="bi bi-person-fill me-1"></i>{{ $a->conductor ? ($a->conductor->nombre . ' ' . $a->conductor->apellido) : 'Sin conductor' }}
                                        · <i class="bi bi-signpost-2 me-1"></i>{{ $a->ruta->nombre ?? 'Sin ruta' }}
                                        · Turno: {{ ucfirst($a->turno->nombre ?? '') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill px-3 py-1 mb-1" style="background: {{ $a->estado_badge['bg'] }}; color: {{ $a->estado_badge['color'] }}; font-weight: 600;">
                                    {{ $a->estado_badge['label'] }}
                                </span>
                                <div class="small text-muted">
                                    Progreso: <strong>{{ $totalCumplidas }}/{{ $totalParadas }} paradas</strong> ({{ $porcentaje }}%)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Barra de Progreso General -->
                        <div class="progress mb-4 control-paradas-progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentaje }}%;" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <!-- Secuencia Horizontal de Paradas -->
                        <div class="row g-3">
                            @foreach($paradasRuta as $p)
                                @php
                                    $control = $controlesCumplidos->firstWhere('ruta_parada_id', $p->pivot->id ?? $p->id);
                                    $esCumplida = ($control !== null);
                                    $esUltima = $loop->last;
                                @endphp
                                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                    <div class="p-3 rounded-3 border h-100 {{ $esCumplida ? 'bg-success-subtle border-success' : 'bg-light' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge {{ $esCumplida ? 'bg-success' : 'bg-secondary' }} rounded-circle control-paradas-stop-order">
                                                {{ $p->pivot->orden ?? $loop->iteration }}
                                            </span>
                                            @if($esCumplida)
                                                <span class="badge bg-success control-paradas-stop-state"><i class="bi bi-check-circle-fill me-1"></i>Cumplida</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-muted control-paradas-stop-state">Pendiente</span>
                                            @endif
                                        </div>
                                        <div class="fw-bold text-dark small text-truncate" title="{{ $p->nombre }}">{{ $p->nombre }}</div>
                                        <div class="text-muted control-paradas-stop-meta">{{ $p->referencia ?? 'Sin referencia' }}</div>
                                        
                                        <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center control-paradas-stop-meta">
                                            @if($esCumplida)
                                                <span class="text-success fw-semibold"><i class="bi bi-clock me-1"></i>{{ $control->fecha_hora->format('H:i:s') }}</span>
                                                <span class="text-muted">{{ $control->distancia_metros }}m</span>
                                            @else
                                                <span class="text-muted">— : —</span>
                                                @if($esUltima)
                                                    <span class="badge bg-primary text-white control-paradas-close-badge">Cierre Automático</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm p-5 text-center text-muted control-paradas-empty-card">
                    <i class="bi bi-pin-map fs-1 d-block mb-2 opacity-50"></i>
                    No hay asignaciones ni recorridos registrados para la fecha seleccionada.
                </div>
            </div>
        @endforelse
    </div>

    @if($asignaciones->hasPages())
        <div class="mt-4">
            {{ $asignaciones->appends(['fecha' => $fecha, 'ruta_id' => $rutaId])->links() }}
        </div>
    @endif
</div>
@endsection
