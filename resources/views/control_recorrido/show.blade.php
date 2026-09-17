@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #mapDetalle { height: 420px; border-radius: 14px; }
    .legend-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }
    .marker-bus { background:#7B1E2B; color:#fff; border:2px solid #fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:13px; box-shadow:0 0 0 4px rgba(123,30,43,.35); animation: pulseBus 2s infinite; }
    @keyframes pulseBus { 0%{ box-shadow:0 0 0 0 rgba(123,30,43,.6); } 70%{ box-shadow:0 0 0 10px rgba(123,30,43,0); } 100%{ box-shadow:0 0 0 0 rgba(123,30,43,0); } }
    .marker-stop { border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; border:2px solid; }
    .marker-stop.cumplida  { background:#10b981; color:#fff; border-color:#059669; }
    .marker-stop.pendiente { background:#f59e0b; color:#fff; border-color:#d97706; }
    .marker-stop.omitida   { background:#ef4444; color:#fff; border-color:#dc2626; }
    .meta-chip { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:0.4rem 0.8rem; font-size:0.82rem; }
    .progress-bar-custom { height:8px; border-radius:10px; background:#e2e8f0; overflow:hidden; }
    .progress-bar-custom .fill { height:100%; border-radius:10px; background: linear-gradient(90deg,#10b981,#3b82f6); transition:width .5s ease; }
    .next-stop-card { border-left: 4px solid #f59e0b; background:#fffbeb; border-radius:0 10px 10px 0; }
    .gps-info { font-size:0.8rem; }
</style>

<div class="container-fluid py-4">

    {{-- Breadcrumb + header --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('control-recorrido.index') }}" class="text-decoration-none">Control de Recorrido</a></li>
            <li class="breadcrumb-item active">{{ $codigo }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('control-recorrido.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0" style="color:var(--primary);">Detalle de Recorrido — {{ $codigo }}</h4>
                <span class="badge rounded-pill px-3 py-1 {{ $asignacion->estado === 'en_curso' ? 'bg-success' : ($asignacion->estado === 'completado' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                    {{ ucfirst(str_replace('_', ' ', $asignacion->estado)) }}
                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('control-recorrido.historial', $asignacion->id) }}" class="btn btn-sm btn-outline-primary px-3" style="border-radius:8px;">
                <i class="bi bi-clock-history me-1"></i>Historial completo
            </a>
            <a href="{{ route('control-recorrido.mapa', $asignacion->id) }}" class="btn btn-sm px-3 text-white" style="border-radius:8px; background:var(--accent);">
                <i class="bi bi-fullscreen me-1"></i>Mapa ampliado
            </a>
        </div>
    </div>

    {{-- Franja de metadatos --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <span class="meta-chip"><i class="bi bi-signpost-2 me-1 text-primary"></i><strong>Ruta:</strong> {{ $asignacion->ruta->nombre ?? '—' }}</span>
        <span class="meta-chip"><i class="bi bi-bus-front me-1 text-primary"></i><strong>Micro:</strong> {{ $asignacion->micro->placa ?? 'S/P' }} – Int. {{ $asignacion->micro->interno->numero_interno ?? 'S/I' }}</span>
        <span class="meta-chip"><i class="bi bi-person-badge me-1 text-primary"></i><strong>Conductor:</strong> {{ $asignacion->conductor?->nombre ?? '—' }} {{ $asignacion->conductor?->apellido ?? '' }}</span>
        <span class="meta-chip"><i class="bi bi-calendar3 me-1 text-primary"></i><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($asignacion->fecha)->format('d/m/Y') }}</span>
        <span class="meta-chip"><i class="bi bi-clock me-1 text-primary"></i><strong>Hora inicio:</strong> {{ $asignacion->hora_salida ?? '—' }}</span>
        <span class="meta-chip text-primary fw-semibold"><i class="bi bi-arrow-right me-1"></i>Fase: {{ $sentidoActivo }}</span>
    </div>

    <div class="row g-4">
        {{-- Mapa --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
                <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-map-fill"></i>
                        <span class="fw-bold">Mapa del Recorrido — {{ $asignacion->ruta->nombre ?? '—' }}</span>
                    </div>
                    <div class="d-flex gap-3 align-items-center small">
                        <span><span class="legend-dot bg-success"></span> Cumplida</span>
                        <span><span class="legend-dot bg-warning"></span> Pendiente</span>
                        <span><span class="legend-dot bg-danger"></span> Omitida</span>
                        <span><span class="legend-dot" style="background:#7B1E2B;"></span> Micro</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="mapDetalle"></div>
                </div>
            </div>
        </div>

        {{-- Panel lateral --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            {{-- Progreso --}}
            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-header py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span class="fw-bold">Progreso del recorrido</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">{{ $paradasCumplidas }} / {{ $totalParadas }} paradas</span>
                        <span class="fw-bold text-primary">{{ $porcentaje }}%</span>
                    </div>
                    <div class="progress-bar-custom mb-4">
                        <div class="fill" style="width: {{ $porcentaje }}%;"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                <div class="text-muted small">Velocidad actual</div>
                                <div class="fw-bold fs-5 text-primary">{{ $ultimoGps ? number_format((float)$ultimoGps->velocidad, 1) : '0.0' }} <small style="font-size:0.7rem;">km/h</small></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                <div class="text-muted small">Fase actual</div>
                                <div class="fw-bold fs-6 text-primary">{{ $sentidoActivo }}</div>
                            </div>
                        </div>
                    </div>
                    @if($ultimoGps)
                        <div class="mt-3 pt-3 border-top gps-info">
                            <div class="text-muted mb-1"><i class="bi bi-geo-alt me-1"></i>Última posición GPS</div>
                            <div class="fw-semibold font-monospace">{{ number_format((float)$ultimoGps->latitud, 4) }}, {{ number_format((float)$ultimoGps->longitud, 4) }}</div>
                            <div class="text-muted">{{ $ultimoGps->fecha_hora_gps->format('d/m/Y H:i:s') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Próxima parada --}}
            @if($siguienteParada && $siguienteParada->parada)
                <div class="card border-0 shadow-sm" style="border-radius:16px;">
                    <div class="card-header py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-warning"></i>
                            <span class="fw-bold">Próxima parada</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="next-stop-card p-3 mb-3">
                            <div class="fw-bold text-dark">{{ $siguienteParada->parada->nombre }}</div>
                            @if($siguienteParada->parada->referencia)
                                <div class="text-muted small">{{ $siguienteParada->parada->referencia }}</div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">⏳ Pendiente</span>
                            <span class="text-muted small">
                                Orden #{{ $siguienteParada->orden }} · {{ $sentidoActivo }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm" style="border-radius:16px;">
                    <div class="card-body p-4 text-center text-muted">
                        <i class="bi bi-check-circle-fill text-success fs-2 d-block mb-2"></i>
                        {{ $porcentaje === 100 ? 'Recorrido completado.' : 'Sin próxima parada configurada.' }}
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var cumplidasIds = @json($cumplidasIds);

    // Unir ambas secuencias de paradas
    var paradasIda    = @json($paradasIdaJson);
    var paradasVuelta = @json($paradasVueltaJson);
    var todasParadas  = paradasIda.concat(paradasVuelta).filter(p => p.lat !== 0 || p.lng !== 0);

    var latGps  = {{ $ultimoGps ? (float)$ultimoGps->latitud  : 'null' }};
    var lngGps  = {{ $ultimoGps ? (float)$ultimoGps->longitud : 'null' }};
    var velGps  = {{ $ultimoGps ? (float)$ultimoGps->velocidad : 0 }};

    var centro = todasParadas.length > 0
        ? [todasParadas[0].lat, todasParadas[0].lng]
        : (latGps ? [latGps, lngGps] : [-17.7830, -63.1820]);

    var map = L.map('mapDetalle').setView(centro, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    var coords = [];
    todasParadas.forEach(function (p) {
        coords.push([p.lat, p.lng]);
        var isCumplida = cumplidasIds.indexOf(p.id) !== -1;
        var cls = isCumplida ? 'cumplida' : 'pendiente';
        var icon = L.divIcon({ className: 'marker-stop ' + cls, html: p.orden, iconSize: [24,24], iconAnchor: [12,12] });
        L.marker([p.lat, p.lng], { icon: icon })
            .bindPopup('<strong>Parada #' + p.orden + ' (' + p.sentido + '): ' + p.nombre + '</strong><br>Estado: ' + (isCumplida ? '✅ Cumplida' : '⏳ Pendiente'))
            .addTo(map);
    });

    if (coords.length > 1) {
        L.polyline(coords, { color: '#3b82f6', weight: 4, opacity: 0.7, dashArray: '8,6' }).addTo(map);
    }

    if (latGps) {
        var busIcon = L.divIcon({ className: 'marker-bus', html: '<i class="bi bi-bus-front" style="font-size:13px;"></i>', iconSize: [34,34], iconAnchor: [17,17] });
        L.marker([latGps, lngGps], { icon: busIcon })
            .bindPopup('<strong>Micro {{ $asignacion->micro->placa ?? "" }}</strong><br>Velocidad: ' + velGps + ' km/h')
            .addTo(map)
            .openPopup();
    }

    if (coords.length > 0) {
        map.fitBounds(coords.length > 1 ? coords : [centro], { padding: [30,30] });
    }
});
</script>
@endsection
