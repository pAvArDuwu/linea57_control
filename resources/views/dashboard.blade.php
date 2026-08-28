@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Aplicar background-color: #1A2D4F a todas las cards del dashboard */
    .card, .card-soft {
        background-color: #1A2D4F !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
    }
    .card-header {
        background-color: #1A2D4F !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    #mapaMonitoreo {
        height: 540px;
        border-radius: 16px;
        z-index: 1;
    }
    .stat-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.3) !important;
    }
    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 1.35rem;
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
    }
    .stat-card.accent .stat-icon {
        background: rgba(248, 113, 113, 0.2) !important;
        color: #f87171 !important;
    }
    .custom-bus-marker {
        background-color: #1A2D4F;
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 6px rgba(26, 45, 79, 0.4), 0 4px 10px rgba(0,0,0,0.4);
        font-size: 15px;
        animation: pulseMarker 2s infinite;
    }
    @keyframes pulseMarker {
        0% { box-shadow: 0 0 0 0 rgba(26, 45, 79, 0.6); }
        70% { box-shadow: 0 0 0 12px rgba(26, 45, 79, 0); }
        100% { box-shadow: 0 0 0 0 rgba(26, 45, 79, 0); }
    }
    .custom-stop-marker {
        background: #334155;
        color: #cbd5e1;
        border: 2px solid #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }
    .custom-stop-marker.cumplida {
        background: #22c55e;
        color: white;
        border-color: #15803d;
    }
    .table-soft thead th {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #94a3b8 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .table-soft tbody td {
        color: #e2e8f0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-semibold mb-1" style="color: var(--primary);">Panel general</h2>
            <p class="text-muted mb-0">Monitoreo operativo y control en tiempo real · Línea 61 Santa Cruz</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm" style="background-color: #1A2D4F; border: 1px solid rgba(255,255,255,0.1);">
                <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                <span class="small fw-semibold text-white">GPS en vivo (5s)</span>
            </div>
            <a href="{{ route('monitoreo.index') }}" class="btn btn-outline-primary px-3 py-2" style="border-radius: 10px;">
                <i class="bi bi-fullscreen me-1"></i>Ver Monitoreo Completo
            </a>
        </div>
    </div>

    <!-- Cards de Estadísticas con background-color: #1A2D4F -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div>
                        <div class="text-white-50 small fw-medium">Micros activos</div>
                        <div class="display-6 fw-semibold mt-1 text-white">{{ $microsActivos ?? 24 }}</div>
                    </div>
                    <div class="stat-icon text-white"><i class="bi bi-bus-front"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card accent h-100 p-4">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div>
                        <div class="text-white-50 small fw-medium">Conductores disponibles</div>
                        <div class="display-6 fw-semibold mt-1 text-white">{{ $conductoresDisponibles ?? 18 }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div>
                        <div class="text-white-50 small fw-medium">Recorridos activos</div>
                        <div class="display-6 fw-semibold mt-1 text-white">{{ $recorridosActivos ?? 11 }}</div>
                    </div>
                    <div class="stat-icon text-white"><i class="bi bi-map"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card accent h-100 p-4">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div>
                        <div class="text-white-50 small fw-medium">Micros fuera de servicio</div>
                        <div class="display-6 fw-semibold mt-1 text-white">{{ $microsFueraServicio ?? 3 }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-tools"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitoreo y Control en Tiempo Real (GPS + Unidades + Paradas) -->
    <div class="row g-4">
        <!-- Mapa de Seguimiento GPS -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-map-fill text-info"></i>
                        <span class="fw-bold text-white">Mapa de Posicionamiento GPS en Tiempo Real</span>
                    </div>
                    <span id="contadorUnidades" class="badge rounded-pill bg-primary px-3 py-1">Cargando unidades...</span>
                </div>
                <div class="card-body p-3">
                    <div id="mapaMonitoreo"></div>
                </div>
            </div>
        </div>

        <!-- Control de Unidades y Paradas en Tiempo Real -->
        <div class="col-12 col-xl-4">
            <!-- Selector de Unidad Activa -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0;">
                    <span class="fw-bold text-white"><i class="bi bi-bus-front-fill text-info me-2"></i>Unidades en Ruta</span>
                    <span class="badge bg-info text-dark rounded-pill px-2 py-1 small">Seleccionar</span>
                </div>
                <div class="card-body p-3" id="listaUnidadesContainer" style="max-height: 240px; overflow-y: auto;">
                    <div class="text-center py-3 text-white-50 small">
                        <span class="spinner-border spinner-border-sm me-2 text-info" role="status"></span>Cargando unidades activas...
                    </div>
                </div>
            </div>

            <!-- Control de Paradas de la Unidad Seleccionada -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0;">
                    <div>
                        <span class="fw-bold text-white"><i class="bi bi-pin-map-fill me-2" style="color: #f87171;"></i>Control de Paradas</span>
                    </div>
                    <span id="paradasProgresoBadge" class="badge bg-success rounded-pill px-3 py-1">0 / 0</span>
                </div>
                <div class="card-body p-3" id="listaParadasContainer" style="max-height: 250px; overflow-y: auto;">
                    <div class="text-center py-4 text-white-50 small">
                        <i class="bi bi-geo-alt fs-2 d-block mb-1 opacity-50"></i>
                        Selecciona una unidad para auditar el paso por sus paradas.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Estado de Flota -->
    <div class="card-soft p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-semibold mb-1 text-white">Estado de la flota y recorridos</h5>
                <p class="text-white-50 small mb-0">Resumen operativo de unidades asignadas</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('control-paradas.index') }}" class="btn btn-sm btn-outline-light" style="border-radius: 8px;">Auditar Paradas</a>
                <a href="{{ route('micro.index') }}" class="btn btn-sm btn-primary" style="border-radius: 8px;">Ver micros</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-soft align-middle mb-0">
                <thead>
                    <tr>
                        <th>Unidad / Placa</th>
                        <th>Conductor</th>
                        <th>Ruta</th>
                        <th>Estado</th>
                        <th>Velocidad</th>
                        <th>Última actualización</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaFlotaBody">
                    <tr>
                        <td colspan="7" class="text-center py-3 text-white-50">Cargando reporte de unidades...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let markers = {};
let routePolyline = null;
let stopMarkers = [];
let unidadSeleccionadaId = null;
let unidadesData = [];

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar Mapa Leaflet centrado en Santa Cruz de la Sierra
    map = L.map('mapaMonitoreo').setView([-17.7830, -63.1820], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Cargar y actualizar posiciones cada 5 segundos
    actualizarPosiciones();
    setInterval(actualizarPosiciones, 5000);
});

async function actualizarPosiciones() {
    try {
        const response = await fetch('{{ route("monitoreo.posiciones") }}');
        const data = await response.json();
        unidadesData = data.unidades || [];

        document.getElementById('contadorUnidades').innerText = `${unidadesData.length} Unidades Activas`;
        renderListaUnidades();
        renderMapaMarkers();
        renderTablaFlota();

        // Refrescar control de paradas si hay unidad seleccionada
        if (unidadSeleccionadaId) {
            const u = unidadesData.find(x => x.asignacion_id === unidadSeleccionadaId);
            if (u) {
                renderControlParadas(u);
            }
        } else if (unidadesData.length > 0) {
            // Seleccionar automáticamente la primera unidad en ruta
            seleccionarUnidad(unidadesData[0].asignacion_id);
        }
    } catch (e) {
        console.error('Error al actualizar monitoreo GPS en tiempo real:', e);
    }
}

function renderListaUnidades() {
    const container = document.getElementById('listaUnidadesContainer');
    if (unidadesData.length === 0) {
        container.innerHTML = `<div class="text-center py-3 text-white-50 small"><i class="bi bi-info-circle me-1"></i>No hay unidades en ruta actualmente.</div>`;
        return;
    }

    let html = '';
    unidadesData.forEach(u => {
        const isSelected = u.asignacion_id === unidadSeleccionadaId;
        html += `
            <div class="p-3 mb-2 rounded-3 border ${isSelected ? 'border-info bg-white bg-opacity-10 shadow-sm' : 'border-secondary bg-white bg-opacity-5'}"
                 onclick="seleccionarUnidad(${u.asignacion_id})" style="cursor: pointer; transition: all 0.2s;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-white">${u.placa} <span class="badge bg-primary text-white border border-primary ms-1">Int. ${u.interno}</span></span>
                    <span class="badge ${u.estado === 'en_curso' ? 'bg-info text-dark' : 'bg-warning text-dark'} text-uppercase" style="font-size: 0.65rem;">${u.estado}</span>
                </div>
                <div class="small text-white-50 text-truncate"><i class="bi bi-person-fill me-1"></i>${u.conductor}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 small text-white-50">
                    <span><i class="bi bi-speedometer2 me-1 text-info"></i>${u.velocidad} km/h</span>
                    <span><i class="bi bi-clock me-1 text-white-50"></i>${u.ultima_actualizacion}</span>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function renderMapaMarkers() {
    unidadesData.forEach(u => {
        if (markers[u.asignacion_id]) {
            markers[u.asignacion_id].setLatLng([u.latitud, u.longitud]);
        } else {
            const icon = L.divIcon({
                className: 'custom-bus-marker',
                html: `<i class="bi bi-bus-front"></i>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
            });

            const marker = L.marker([u.latitud, u.longitud], { icon: icon })
                .bindPopup(`
                    <div class="p-1 text-dark">
                        <strong>${u.placa} (Int. ${u.interno})</strong><br>
                        <span>Conductor: ${u.conductor}</span><br>
                        <span>Ruta: ${u.ruta} (${u.sentido})</span><br>
                        <span>Velocidad: ${u.velocidad} km/h</span><br>
                        <span>Actualizado: ${u.ultima_actualizacion}</span>
                    </div>
                `)
                .on('click', () => seleccionarUnidad(u.asignacion_id))
                .addTo(map);

            markers[u.asignacion_id] = marker;
        }
    });
}

function seleccionarUnidad(asignacionId) {
    unidadSeleccionadaId = asignacionId;
    const u = unidadesData.find(x => x.asignacion_id === asignacionId);
    if (!u) return;

    renderListaUnidades();
    renderControlParadas(u);

    // Centrar mapa en la unidad
    map.setView([u.latitud, u.longitud], 15);

    // Dibujar paradas de la ruta
    stopMarkers.forEach(m => map.removeLayer(m));
    stopMarkers = [];

    if (u.paradas && u.paradas.length > 0) {
        const routeCoords = [];

        u.paradas.forEach((p) => {
            routeCoords.push([p.latitud, p.longitud]);

            const stopIcon = L.divIcon({
                className: `custom-stop-marker ${p.cumplida ? 'cumplida' : ''}`,
                html: `${p.orden}`,
                iconSize: [24, 24],
                iconAnchor: [12, 12],
            });

            const sm = L.marker([p.latitud, p.longitud], { icon: stopIcon })
                .bindPopup(`
                    <div class="text-dark">
                        <strong>Parada #${p.orden}: ${p.nombre}</strong><br>
                        <span>Estado: ${p.cumplida ? '✅ Cumplida (' + p.hora_cumplida + ')' : '⏳ Pendiente'}</span>
                    </div>
                `)
                .addTo(map);

            stopMarkers.push(sm);
        });

        if (routePolyline) {
            map.removeLayer(routePolyline);
        }
        routePolyline = L.polyline(routeCoords, { color: '#60a5fa', weight: 4, opacity: 0.8, dashArray: '6, 6' }).addTo(map);
    }
}

function renderControlParadas(u) {
    const badge = document.getElementById('paradasProgresoBadge');
    const container = document.getElementById('listaParadasContainer');

    badge.innerText = `${u.paradas_cumplidas} / ${u.total_paradas}`;

    if (!u.paradas || u.paradas.length === 0) {
        container.innerHTML = `<div class="text-center py-3 text-white-50 small">Esta ruta no tiene paradas configuradas.</div>`;
        return;
    }

    let html = '';
    u.paradas.forEach((p, idx) => {
        const isLast = idx === u.paradas.length - 1;
        html += `
            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded-2 ${p.cumplida ? 'bg-success bg-opacity-25 border border-success' : 'bg-white bg-opacity-5 border border-secondary'}">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge ${p.cumplida ? 'bg-success' : 'bg-secondary'} rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                        ${p.orden}
                    </span>
                    <div>
                        <div class="fw-semibold text-white small">${p.nombre}</div>
                        ${isLast ? '<span class="badge bg-info text-dark" style="font-size: 0.65rem;">Cierre Automático</span>' : ''}
                    </div>
                </div>
                <div>
                    ${p.cumplida
                        ? `<span class="badge bg-success small"><i class="bi bi-check-lg me-1"></i>${p.hora_cumplida || 'Cumplido'}</span>`
                        : `<span class="badge bg-secondary text-white-50 small">Pendiente</span>`
                    }
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function renderTablaFlota() {
    const tbody = document.getElementById('tablaFlotaBody');
    if (unidadesData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td class="fw-semibold text-white">Micro 201 <span class="badge bg-primary text-white border border-primary ms-1">Int. 201</span></td>
                <td class="text-white-50">René Morales</td>
                <td class="text-white-50">Ruta 61 (Ida)</td>
                <td><span class="badge rounded-pill px-3 py-2 bg-info bg-opacity-25 text-info" style="font-size: 0.75rem;">EN RECORRIDO</span></td>
                <td class="text-white">38 km/h</td>
                <td class="text-white-50">Reciente</td>
                <td><a href="{{ route('monitoreo.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-info fw-semibold">Ver en mapa</a></td>
            </tr>
            <tr>
                <td class="fw-semibold text-white">Micro 145 <span class="badge bg-primary text-white border border-primary ms-1">Int. 145</span></td>
                <td class="text-white-50">María Paredes</td>
                <td class="text-white-50">Ruta 61 (Vuelta)</td>
                <td><span class="badge rounded-pill px-3 py-2 bg-warning bg-opacity-25 text-warning" style="font-size: 0.75rem;">EN TERMINAL</span></td>
                <td class="text-white">0 km/h</td>
                <td class="text-white-50">Hace 10m</td>
                <td><a href="{{ route('monitoreo.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-info fw-semibold">Ver en mapa</a></td>
            </tr>
        `;
        return;
    }

    let html = '';
    unidadesData.forEach(u => {
        html += `
            <tr>
                <td class="fw-semibold text-white">${u.placa} <span class="badge bg-primary text-white border border-primary ms-1">Int. ${u.interno}</span></td>
                <td class="text-white-50">${u.conductor}</td>
                <td class="text-white-50">${u.ruta} (${u.sentido})</td>
                <td><span class="badge rounded-pill px-3 py-2 text-uppercase bg-info bg-opacity-25 text-info" style="font-size: 0.75rem;">${u.estado}</span></td>
                <td class="text-white">${u.velocidad} km/h</td>
                <td class="text-white-50">${u.ultima_actualizacion}</td>
                <td><button onclick="seleccionarUnidad(${u.asignacion_id})" class="btn btn-sm btn-link text-decoration-none p-0 text-info fw-semibold">Ver en mapa</button></td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}
</script>
@endsection
