@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@vite('resources/css/dashboard.css')

<div class="container-fluid py-4">

    <!-- Header Principal -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-grid-1x2-fill me-2" style="color: var(--primary, #0B3C78);"></i>Panel de Control Operativo
                </h4>
            </div>
            <p class="text-muted small mb-0 mt-1">Línea 61 · Monitoreo de flota y auditoría de recorridos en tiempo real</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-white border shadow-sm">
                <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                <span class="small fw-semibold text-muted">GPS en vivo</span>
            </div>
            <a href="{{ route('control-recorrido.index') }}" class="btn text-white px-3 py-2 rounded-3 d-flex align-items-center gap-2 shadow-sm" style="background: var(--accent, #7B1E2B);">
                <i class="bi bi-signpost-split"></i>
                <span>Control de Recorrido</span>
            </a>
        </div>
    </div>

    <!-- 4 Cards de Métricas -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-stat-card card-border-navy">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Micros en Servicio</div>
                        <div class="h3 fw-bold mt-1 text-dark mb-0">{{ $microsActivos ?? 24 }}</div>
                    </div>
                    <div class="stat-icon-circle icon-bg-navy">
                        <i class="bi bi-bus-front"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">
                        <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i> En circulación
                    </span>
                    <span class="small text-muted">Flota activa</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-stat-card card-border-wine">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Conductores Activos</div>
                        <div class="h3 fw-bold mt-1 text-dark mb-0">{{ $conductoresDisponibles ?? 18 }}</div>
                    </div>
                    <div class="stat-icon-circle icon-bg-wine">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="badge rounded-pill px-2 py-1 small" style="background:#FDE8EC; color:#7B1E2B;">
                        En turno hoy
                    </span>
                    <span class="small text-muted">Personal asignado</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-stat-card card-border-teal">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Recorridos en Curso</div>
                        <div class="h3 fw-bold mt-1 text-dark mb-0">{{ $recorridosActivos ?? 11 }}</div>
                    </div>
                    <div class="stat-icon-circle icon-bg-teal">
                        <i class="bi bi-pin-map"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1 small">
                        Ida & Vuelta
                    </span>
                    <span class="small text-muted">Supervisados GPS</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-stat-card card-border-amber">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Fuera de Servicio</div>
                        <div class="h3 fw-bold mt-1 text-dark mb-0">{{ $microsFueraServicio ?? 3 }}</div>
                    </div>
                    <div class="stat-icon-circle icon-bg-amber">
                        <i class="bi bi-tools"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="badge bg-warning bg-opacity-20 text-warning-emphasis rounded-pill px-2 py-1 small">
                        Mantenimiento
                    </span>
                    <span class="small text-muted">En taller / reserva</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Destacado: Transacción 2 - Control de Recorrido -->
    <div class="banner-cr mb-4 shadow-sm">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-dark fw-bold px-2 py-1" style="font-size:0.75rem;">Segunda Transacción SDD</span>
                    <span class="text-white-50 small">Auditoría automática de paradas e intervalos</span>
                </div>
                <h5 class="fw-bold mb-1">Módulo de Control de Recorrido</h5>
                <p class="text-white-50 small mb-0">Evalúa el cumplimiento de itinerarios en tiempo real mediante GPS, registrando si el micro ingresó en el radio de cada parada autorizada en orden.</p>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="{{ route('control-recorrido.index') }}" class="btn btn-light fw-semibold px-4 py-2" style="border-radius:10px; color: #182C4D;">
                    <i class="bi bi-arrow-right-circle-fill me-1" style="color: var(--accent, #7B1E2B);"></i>Ir a Control de Recorrido
                </a>
            </div>
        </div>
    </div>

    <!-- Sección Monitoreo GPS + Paneles Laterales -->
    <div class="row g-4 mb-4">
        <!-- Mapa Principal -->
        <div class="col-12 col-xl-8">
            <div class="panel-card h-100">
                <div class="panel-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        <span class="fw-bold">Mapa de Posicionamiento GPS en Tiempo Real</span>
                    </div>
                    <span id="contadorUnidades" class="badge px-3 py-1 rounded-pill" style="background: var(--accent, #7B1E2B);">Cargando...</span>
                </div>
                <div class="p-3">
                    <div id="mapaMonitoreo"></div>
                </div>
            </div>
        </div>

        <!-- Columna de Control Lateral -->
        <div class="col-12 col-xl-4 d-flex flex-column gap-3">
            <!-- Unidades en Ruta -->
            <div class="panel-card flex-grow-1">
                <div class="panel-card-header d-flex justify-content-between align-items-center py-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bus-front text-info"></i>
                        <span class="fw-bold small">Unidades en Ruta</span>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill px-2 py-1 small">Seleccionar</span>
                </div>
                <div class="p-3" id="listaUnidadesContainer" style="max-height: 235px; overflow-y: auto;">
                    <div class="text-center py-3 text-muted small">
                        <span class="spinner-border spinner-border-sm me-2 text-primary" role="status"></span>Cargando unidades...
                    </div>
                </div>
            </div>
            <!-- Paradas de la Unidad Seleccionada -->
            <div class="panel-card flex-grow-1">
                <div class="panel-card-header d-flex justify-content-between align-items-center py-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pin-map text-warning"></i>
                        <span class="fw-bold small">Control de Paradas</span>
                    </div>
                    <span id="paradasProgresoBadge" class="badge bg-white text-dark rounded-pill px-2 py-1 small fw-bold">0 / 0</span>
                </div>
                <div class="p-3" id="listaParadasContainer" style="max-height: 235px; overflow-y: auto;">
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-geo-alt fs-2 d-block mb-1 opacity-25"></i>
                        Selecciona una unidad para ver el paso por sus paradas.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla: Estado de la Flota -->
    <div class="card border-0 shadow-sm" style="border-radius:16px;">
        <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-0">Estado de la Flota y Recorridos</h6>
                <small class="text-white-50">Resumen operativo de unidades y asignaciones del día</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('control-recorrido.index') }}" class="btn btn-sm btn-outline-light px-3" style="border-radius:8px;">
                    <i class="bi bi-signpost-split me-1"></i>Ver Todos los Recorridos
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-dash align-middle mb-0">
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
                        <td colspan="7" class="text-center py-4 text-muted">Cargando flota...</td>
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
    map = L.map('mapaMonitoreo').setView([-17.7830, -63.1820], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    actualizarPosiciones();
    setInterval(actualizarPosiciones, 5000);
});

async function actualizarPosiciones() {
    try {
        const response = await fetch('{{ route("monitoreo.posiciones") }}');
        const data = await response.json();
        unidadesData = data.unidades || [];

        const badge = document.getElementById('contadorUnidades');
        if (badge) badge.innerText = `${unidadesData.length} Unidades Activas`;

        renderListaUnidades();
        renderMapaMarkers();
        renderTablaFlota();

        // Refrescar control de paradas

        if (unidadSeleccionadaId) {
            const u = unidadesData.find(x => x.asignacion_id === unidadSeleccionadaId);
            if (u) renderControlParadas(u);
        } else if (unidadesData.length > 0) {
            seleccionarUnidad(unidadesData[0].asignacion_id);
        }
    } catch (e) {
        console.error('Error al actualizar monitoreo GPS en tiempo real:', e);
    }
}

function renderListaUnidades() {
    const container = document.getElementById('listaUnidadesContainer');
    if (!container) return;

    if (unidadesData.length === 0) {
        container.innerHTML = `<div class="text-center py-3 text-muted small"><i class="bi bi-info-circle me-1"></i>No hay unidades en ruta actualmente.</div>`;
        return;
    }

    let html = '';
    unidadesData.forEach(u => {
        const isSelected = u.asignacion_id === unidadSeleccionadaId;
        html += `
            <div class="unit-card ${isSelected ? 'selected' : ''}" onclick="seleccionarUnidad(${u.asignacion_id})">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-dark">${u.placa} <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1">Int. ${u.interno}</span></span>
                    <span class="badge ${u.estado === 'en_curso' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-20 text-dark'} text-uppercase" style="font-size: 0.65rem;">${u.estado}</span>
                </div>
                <div class="small text-muted text-truncate mb-1"><i class="bi bi-person me-1"></i>${u.conductor}</div>
                <div class="d-flex justify-content-between align-items-center small">
                    <span class="fw-semibold text-dark"><i class="bi bi-speedometer2 me-1" style="color: var(--accent, #7B1E2B);"></i>${u.velocidad} km/h</span>
                    <span class="text-muted"><i class="bi bi-clock me-1"></i>${u.ultima_actualizacion}</span>
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
                    <div class="p-1">
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

    map.setView([u.latitud, u.longitud], 15);

    stopMarkers.forEach(m => map.removeLayer(m));
    stopMarkers = [];

    if (u.paradas && u.paradas.length > 0) {
        const routeCoords = [];

        u.paradas.forEach((p) => {
            routeCoords.push([p.latitud, p.longitud]);

            const stopIcon = L.divIcon({
                className: `custom-stop-marker ${p.cumplida ? 'cumplida' : ''}`,
                html: `${p.orden}`,
                iconSize: [22, 22],
                iconAnchor: [11, 11],
            });

            const sm = L.marker([p.latitud, p.longitud], { icon: stopIcon })
                .bindPopup(`
                    <div>
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
        routePolyline = L.polyline(routeCoords, { color: '#3b82f6', weight: 4, opacity: 0.8, dashArray: '6, 6' }).addTo(map);
    }
}

function renderControlParadas(u) {
    const badge = document.getElementById('paradasProgresoBadge');
    const container = document.getElementById('listaParadasContainer');
    if (!badge || !container) return;


    badge.innerText = `${u.paradas_cumplidas} / ${u.total_paradas}`;

    if (!u.paradas || u.paradas.length === 0) {
        container.innerHTML = `<div class="text-center py-3 text-muted small">Esta ruta no tiene paradas configuradas.</div>`;
        return;
    }

    let html = '';
    u.paradas.forEach((p, idx) => {
        const isLast = idx === u.paradas.length - 1;
        html += `
            <div class="stop-card-item d-flex align-items-center justify-content-between ${p.cumplida ? 'cumplida' : 'pendiente'}">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge ${p.cumplida ? 'bg-success' : 'bg-primary'} rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem;">
                        ${p.orden}
                    </span>
                    <div>
                        <div class="fw-semibold text-dark small">${p.nombre}</div>
                        ${isLast ? '<span class="badge bg-secondary text-white" style="font-size: 0.65rem;">Cierre Automático</span>' : ''}
                    </div>
                </div>
                <div>
                    ${p.cumplida
                        ? `<span class="badge bg-success small"><i class="bi bi-check-lg me-1"></i>${p.hora_cumplida || 'Cumplido'}</span>`
                        : `<span class="badge bg-light text-muted border small">Pendiente</span>`
                    }
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function renderTablaFlota() {
    const tbody = document.getElementById('tablaFlotaBody');
    if (!tbody) return;

    if (unidadesData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">No hay unidades activas en este momento.</td>
            </tr>
        `;
        return;
    }

    let html = '';
    unidadesData.forEach(u => {
        const urlRecorrido = `/control-recorrido/${u.asignacion_id}`;
        html += `
            <tr>
                <td><span class="fw-bold text-dark">${u.placa}</span> <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1">Int. ${u.interno}</span></td>
                <td><span class="text-dark fw-medium">${u.conductor}</span></td>
                <td><span class="text-muted">${u.ruta} (${u.sentido})</span></td>
                <td><span class="badge rounded-pill px-3 py-1 text-uppercase ${u.estado === 'en_curso' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-warning bg-opacity-20 text-dark'}" style="font-size: 0.75rem;">${u.estado}</span></td>
                <td><span class="fw-bold text-dark">${u.velocidad} km/h</span></td>
                <td><span class="text-muted small">${u.ultima_actualizacion}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <button onclick="seleccionarUnidad(${u.asignacion_id})" class="btn btn-sm btn-outline-primary px-2 py-1" style="border-radius:6px;" title="Ver en mapa">
                            <i class="bi bi-geo-alt-fill"></i>
                        </button>
                        <a href="${urlRecorrido}" class="btn btn-sm px-2 py-1 text-white" style="border-radius:6px; background:var(--accent, #7B1E2B);" title="Control de Recorrido">
                            <i class="bi bi-signpost-split"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}
</script>
@endsection
