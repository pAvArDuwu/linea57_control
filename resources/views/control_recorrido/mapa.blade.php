@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    body { overflow: hidden; }
    #mapaAmpliado { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; }
    .overlay-header {
        position: fixed; top: 0; left: 0; right: 0; z-index: 500;
        background: rgba(255,255,255,0.96); backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0; padding: 0.65rem 1.2rem;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .panel-left {
        position: fixed; top: 58px; left: 0; bottom: 0; width: 280px; z-index: 400;
        background: rgba(255,255,255,0.97); backdrop-filter: blur(8px);
        border-right: 1px solid #e2e8f0; overflow-y: auto; padding: 0;
        transition: transform .25s ease;
    }
    .panel-left.collapsed { transform: translateX(-280px); }
    .panel-right {
        position: fixed; top: 58px; right: 0; bottom: 0; width: 280px; z-index: 400;
        background: rgba(255,255,255,0.97); backdrop-filter: blur(8px);
        border-left: 1px solid #e2e8f0; overflow-y: auto; padding: 0;
        transition: transform .25s ease;
    }
    .panel-toggle-left  { position: fixed; top: 50%; left: 285px; transform: translateY(-50%); z-index: 410; transition: left .25s ease; }
    .panel-toggle-left.collapsed-pos { left: 5px; }
    .parada-item-mapa {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.65rem 1rem; border-bottom: 1px solid #f1f5f9;
        cursor: pointer; transition: background .15s ease; font-size: 0.85rem;
    }
    .parada-item-mapa:hover { background: #f8fafc; }
    .dot-cumplida  { width:10px;height:10px;border-radius:50%;background:#10b981;flex-shrink:0; }
    .dot-pendiente { width:10px;height:10px;border-radius:50%;background:#f59e0b;flex-shrink:0; }
    .dot-omitida   { width:10px;height:10px;border-radius:50%;background:#ef4444;flex-shrink:0; }
    .marker-bus { background:#7B1E2B;color:#fff;border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;box-shadow:0 0 0 4px rgba(123,30,43,.4);animation:pbm 2s infinite; }
    @keyframes pbm { 0%{box-shadow:0 0 0 0 rgba(123,30,43,.6);}70%{box-shadow:0 0 0 10px rgba(123,30,43,0);}100%{box-shadow:0 0 0 0 rgba(123,30,43,0);} }
    .marker-stop { border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;border:2px solid; }
    .marker-stop.cumplida  { background:#10b981;color:#fff;border-color:#059669; }
    .marker-stop.pendiente { background:#f59e0b;color:#fff;border-color:#d97706; }
    .panel-section { padding: 0.9rem 1rem 0.5rem; border-bottom: 1px solid #f1f5f9; }
    .panel-section-title { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; font-weight: 700; margin-bottom: 0.5rem; }
    .stat-mini { text-align: center; }
    .stat-mini .val { font-size: 1.3rem; font-weight: 700; line-height: 1; }
    .stat-mini .lbl { font-size: 0.72rem; color: #64748b; }
    .progress-mini { height: 6px; border-radius: 10px; background: #e2e8f0; overflow: hidden; margin-top: 0.4rem; }
    .progress-mini .fill { height: 100%; border-radius: 10px; background: linear-gradient(90deg,#10b981,#3b82f6); }
    .gps-info { font-size: 0.78rem; color: #64748b; }
</style>

{{-- Header superpuesto --}}
<div class="overlay-header">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('control-recorrido.show', $asignacion->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <strong class="text-primary">{{ $codigo }}</strong>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $asignacion->ruta->nombre ?? '—' }}</span>
        <span class="badge {{ $asignacion->estado === 'en_curso' ? 'bg-success' : ($asignacion->estado === 'completado' ? 'bg-secondary' : 'bg-warning text-dark') }}">
            {{ ucfirst(str_replace('_','_',$asignacion->estado)) }}
        </span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('control-recorrido.historial', $asignacion->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
            <i class="bi bi-clock-history me-1"></i>Historial
        </a>
        <a href="{{ route('control-recorrido.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>
</div>

{{-- Mapa de fondo --}}
<div id="mapaAmpliado"></div>

{{-- Toggle panel izquierdo --}}
<div class="panel-toggle-left" id="toggleLeft">
    <button class="btn btn-sm btn-white shadow border rounded-circle p-1" onclick="togglePanelLeft()" title="Mostrar/ocultar paradas" style="width:32px;height:32px;">
        <i class="bi bi-layout-sidebar" style="font-size:0.9rem;"></i>
    </button>
</div>

{{-- Panel izquierdo: Paradas --}}
<div class="panel-left" id="panelLeft">
    {{-- Paradas Ida --}}
    <div class="panel-section">
        <div class="panel-section-title">
            <button class="btn btn-link p-0 text-muted fw-bold d-flex justify-content-between w-100" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;" data-bs-toggle="collapse" data-bs-target="#colIda">
                <span><i class="bi bi-arrow-right me-1"></i>Ruta {{ $asignacion->ruta->nombre ?? '' }} (Ida) · {{ $paradasIda->count() }}</span>
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="colIda">
            @forelse($paradasIda as $rp)
                @php $cumplida = in_array($rp->id, $cumplidasIds); @endphp
                <div class="parada-item-mapa" onclick="flyTo({{ (float)($rp->parada->latitud ?? 0) }}, {{ (float)($rp->parada->longitud ?? 0) }}, '{{ addslashes($rp->parada->nombre ?? '?') }}')">
                    <span class="{{ $cumplida ? 'dot-cumplida' : 'dot-pendiente' }}"></span>
                    <span class="text-muted fw-semibold" style="min-width:20px;">{{ $rp->orden }}</span>
                    <span class="{{ $cumplida ? 'text-success' : '' }}">{{ $rp->parada->nombre ?? '—' }}</span>
                </div>
            @empty
                <div class="p-3 text-muted small">Sin paradas de Ida configuradas.</div>
            @endforelse
        </div>
    </div>

    {{-- Paradas Vuelta --}}
    @if($paradasVuelta->isNotEmpty())
    <div class="panel-section">
        <div class="panel-section-title">
            <button class="btn btn-link p-0 text-muted fw-bold d-flex justify-content-between w-100" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;" data-bs-toggle="collapse" data-bs-target="#colVuelta">
                <span><i class="bi bi-arrow-left me-1"></i>Ruta {{ $asignacion->ruta->nombre ?? '' }} (Vuelta) · {{ $paradasVuelta->count() }}</span>
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="colVuelta">
            @foreach($paradasVuelta as $rp)
                @php $cumplida = in_array($rp->id, $cumplidasIds); @endphp
                <div class="parada-item-mapa" onclick="flyTo({{ (float)($rp->parada->latitud ?? 0) }}, {{ (float)($rp->parada->longitud ?? 0) }}, '{{ addslashes($rp->parada->nombre ?? '?') }}')">
                    <span class="{{ $cumplida ? 'dot-cumplida' : 'dot-pendiente' }}"></span>
                    <span class="text-muted fw-semibold" style="min-width:20px;">{{ $rp->orden }}</span>
                    <span class="{{ $cumplida ? 'text-success' : '' }}">{{ $rp->parada->nombre ?? '—' }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="p-3">
        <a href="{{ route('control-recorrido.historial', $asignacion->id) }}" class="btn btn-sm btn-outline-primary w-100" style="border-radius:8px;">
            <i class="bi bi-list-ol me-1"></i>Ver detalle de paradas
        </a>
    </div>
</div>

{{-- Panel derecho: Info del micro y resumen --}}
<div class="panel-right" id="panelRight">
    {{-- Info del micro --}}
    <div class="panel-section">
        <div class="panel-section-title">Información del micro</div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width:36px;height:36px;background:var(--primary);">
                <i class="bi bi-bus-front" style="font-size:1rem;"></i>
            </div>
            <div>
                <div class="fw-bold">{{ $asignacion->micro->placa ?? 'S/P' }}</div>
                <div class="text-muted small">Int. {{ $asignacion->micro->interno->numero_interno ?? 'S/I' }}</div>
            </div>
            <span class="badge ms-auto {{ $asignacion->estado === 'en_curso' ? 'bg-success' : 'bg-secondary' }}" style="font-size:0.7rem;">
                {{ ucfirst(str_replace('_',' ',$asignacion->estado)) }}
            </span>
        </div>
        <div class="gps-info mt-2">
            <div><i class="bi bi-person me-1"></i>{{ $asignacion->conductor?->nombre ?? '—' }} {{ $asignacion->conductor?->apellido ?? '' }}</div>
            @if($ultimoGps)
                <div class="mt-1"><i class="bi bi-speedometer2 me-1"></i><strong>{{ number_format((float)$ultimoGps->velocidad, 1) }} km/h</strong></div>
                <div><i class="bi bi-geo me-1"></i>{{ number_format((float)$ultimoGps->latitud, 4) }}, {{ number_format((float)$ultimoGps->longitud, 4) }}</div>
                <div class="text-muted">{{ $ultimoGps->fecha_hora_gps->format('d/m H:i:s') }}</div>
            @else
                <div class="text-muted mt-1"><i class="bi bi-wifi-off me-1"></i>Sin señal GPS reciente</div>
            @endif
        </div>
    </div>

    {{-- Resumen del recorrido --}}
    <div class="panel-section">
        <div class="panel-section-title">Resumen del recorrido</div>
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="stat-mini">
                    <div class="val text-success">{{ $cumplidas }}</div>
                    <div class="lbl">Cumplidas</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-mini">
                    <div class="val text-warning">{{ $pendientes }}</div>
                    <div class="lbl">Pendientes</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-mini">
                    <div class="val text-danger">{{ $omitidas }}</div>
                    <div class="lbl">Omitidas</div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between small mb-1">
            <span class="text-muted">Total</span>
            <span class="fw-bold">{{ $total }}</span>
        </div>
        <div class="d-flex justify-content-between small mb-2">
            <span class="text-muted">Progreso</span>
            <span class="fw-bold text-primary">{{ $porcentaje }}%</span>
        </div>
        <div class="progress-mini"><div class="fill" style="width:{{ $porcentaje }}%;"></div></div>
    </div>

    {{-- Acciones --}}
    <div class="p-3 d-flex flex-column gap-2">
        <a href="{{ route('control-recorrido.historial', $asignacion->id) }}" class="btn btn-sm btn-outline-primary w-100" style="border-radius:8px;">
            <i class="bi bi-clock-history me-1"></i>Historial completo
        </a>
        <a href="{{ route('control-recorrido.show', $asignacion->id) }}" class="btn btn-sm btn-outline-secondary w-100" style="border-radius:8px;">
            <i class="bi bi-bar-chart me-1"></i>Ver progreso
        </a>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var cumplidasIds  = @json($cumplidasIds);
var paradasIda    = @json($paradasIdaJson);
var paradasVuelta = @json($paradasVueltaJson);
var todasParadas  = paradasIda.concat(paradasVuelta).filter(p => p.lat !== 0 || p.lng !== 0);
var latGps = {{ $ultimoGps ? (float)$ultimoGps->latitud  : 'null' }};
var lngGps = {{ $ultimoGps ? (float)$ultimoGps->longitud : 'null' }};
var velGps = {{ $ultimoGps ? (float)$ultimoGps->velocidad : 0 }};

var centro = todasParadas.length > 0 ? [todasParadas[0].lat, todasParadas[0].lng]
           : (latGps ? [latGps, lngGps] : [-17.7830, -63.1820]);

var map = L.map('mapaAmpliado').setView(centro, 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

var coords = [];
todasParadas.forEach(function (p) {
    if (!p.lat && !p.lng) return;
    coords.push([p.lat, p.lng]);
    var isCumplida = cumplidasIds.indexOf(p.id) !== -1;
    var cls  = isCumplida ? 'cumplida' : 'pendiente';
    var icon = L.divIcon({ className: 'marker-stop ' + cls, html: p.orden, iconSize: [24,24], iconAnchor: [12,12] });
    L.marker([p.lat, p.lng], { icon: icon })
        .bindPopup('<strong>#' + p.orden + ' (' + p.sentido + '): ' + p.nombre + '</strong><br>' + (isCumplida ? '✅ Cumplida' : '⏳ Pendiente'))
        .addTo(map);
});

if (coords.length > 1) {
    L.polyline(coords, { color: '#3b82f6', weight: 4, opacity: 0.7, dashArray:'8,6' }).addTo(map);
}

if (latGps) {
    var busIcon = L.divIcon({ className: 'marker-bus', html: '<i class="bi bi-bus-front" style="font-size:13px;"></i>', iconSize: [34,34], iconAnchor: [17,17] });
    L.marker([latGps, lngGps], { icon: busIcon })
        .bindPopup('<strong>Micro en ruta</strong><br>Velocidad: ' + velGps + ' km/h')
        .addTo(map).openPopup();
}

if (coords.length > 0) {
    map.fitBounds(coords.length > 1 ? coords : [centro], { padding: [60,310] });
}

function flyTo(lat, lng, nombre) {
    if (lat === 0 && lng === 0) return;
    map.flyTo([lat, lng], 16, { duration: 0.8 });
}

var panelLeftEl  = document.getElementById('panelLeft');
var toggleLeftEl = document.getElementById('toggleLeft');
var leftCollapsed = false;

function togglePanelLeft() {
    leftCollapsed = !leftCollapsed;
    panelLeftEl.classList.toggle('collapsed', leftCollapsed);
    toggleLeftEl.classList.toggle('collapsed-pos', leftCollapsed);
}
</script>
@endsection
