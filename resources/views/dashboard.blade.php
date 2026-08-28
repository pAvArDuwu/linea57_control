@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Paleta Coherente Dashboard: Deep Navy Card (#182C4D), Blanco (#FFFFFF), Guindo/Rojo Vino (#7B1E2B / #941B2D) */
    .dashboard-card {
        background-color: #182C4D !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.09) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.2) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card-header-custom {
        background-color: #142540 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        border-radius: 16px 16px 0 0 !important;
    }

    /* Stat Cards estilizadas */
    .stat-card-custom {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.4rem 1.4rem 1.2rem 1.4rem;
        position: relative;
        overflow: hidden;
    }
    .stat-card-custom:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.35) !important;
    }
    
    /* Variaciones temáticas coherentes */
    .stat-card-blue {
        border-top: 4px solid #3B82F6 !important;
    }
    .stat-card-wine {
        border-top: 4px solid #8B1E2F !important;
    }
    .stat-card-cyan {
        border-top: 4px solid #0284C7 !important;
    }
    .stat-card-guindo {
        border-top: 4px solid #A61C2E !important;
    }

    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 1.4rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .stat-icon-blue {
        background: rgba(59, 130, 246, 0.18);
        color: #60A5FA;
        border: 1px solid rgba(96, 165, 250, 0.3);
    }
    .stat-icon-wine {
        background: linear-gradient(135deg, #7B1E2B 0%, #A61C2E 100%);
        color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .stat-icon-cyan {
        background: rgba(2, 132, 199, 0.22);
        color: #38BDF8;
        border: 1px solid rgba(56, 189, 248, 0.3);
    }
    .stat-icon-guindo {
        background: linear-gradient(135deg, #8B1E2F 0%, #C53047 100%);
        color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    /* Badges y acentos */
    .badge-guindo {
        background-color: #7B1E2B !important;
        color: #FFFFFF !important;
    }
    .badge-guindo-subtle {
        background-color: rgba(123, 30, 43, 0.25) !important;
        color: #F87171 !important;
        border: 1px solid rgba(123, 30, 43, 0.4);
    }
    .badge-blue-subtle {
        background-color: rgba(59, 130, 246, 0.2) !important;
        color: #93C5FD !important;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    .badge-success-subtle {
        background-color: rgba(16, 185, 129, 0.2) !important;
        color: #6EE7B7 !important;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    /* Botón Guindo */
    .btn-guindo {
        background: linear-gradient(135deg, #7B1E2B 0%, #941B2D 100%);
        color: #FFFFFF;
        border: 1px solid rgba(255,255,255,0.15);
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-guindo:hover {
        background: linear-gradient(135deg, #941B2D 0%, #B91C1C 100%);
        color: #FFFFFF;
        box-shadow: 0 4px 14px rgba(123, 30, 43, 0.4);
    }

    #mapaMonitoreo {
        height: 520px;
        border-radius: 14px;
        z-index: 1;
    }
    
    .custom-bus-marker {
        background-color: #7B1E2B;
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 5px rgba(123, 30, 43, 0.45), 0 4px 10px rgba(0,0,0,0.4);
        font-size: 15px;
        animation: pulseMarker 2s infinite;
    }
    @keyframes pulseMarker {
        0% { box-shadow: 0 0 0 0 rgba(123, 30, 43, 0.6); }
        70% { box-shadow: 0 0 0 12px rgba(123, 30, 43, 0); }
        100% { box-shadow: 0 0 0 0 rgba(123, 30, 43, 0); }
    }
    .custom-stop-marker {
        background: #1e293b;
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
        background: #10B981;
        color: white;
        border-color: #059669;
    }
    /* Estilos de Tabla y Listas dentro de Cards Azules (Fondo Blanco, Datos en Negro, Acciones en Guindo) */
    .inner-white-container {
        background-color: #FFFFFF !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
        overflow: hidden;
    }
    .table-white-custom {
        --bs-table-bg: #FFFFFF !important;
        --bs-table-accent-bg: transparent !important;
        --bs-table-striped-bg: #F8FAFC !important;
        --bs-table-hover-bg: #F1F5F9 !important;
        --bs-table-color: #0F172A !important;
        background-color: #FFFFFF !important;
        color: #0F172A !important;
        border-color: #E2E8F0 !important;
        margin-bottom: 0 !important;
    }
    .table-white-custom > :not(caption) > * > * {
        background-color: transparent !important;
        color: #0F172A !important;
        box-shadow: none !important;
    }
    .table-white-custom thead th {
        background-color: #F8FAFC !important;
        color: #334155 !important;
        border-bottom: 2px solid #E2E8F0 !important;
        border-top: none !important;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.95rem 1rem !important;
    }
    .table-white-custom tbody tr {
        border-bottom: 1px solid #F1F5F9 !important;
        transition: background-color 0.15s ease;
    }
    .table-white-custom tbody tr:hover td {
        background-color: #F1F5F9 !important;
    }
    .table-white-custom tbody td {
        color: #0F172A !important;
        border-bottom: 1px solid #F1F5F9 !important;
        font-size: 0.92rem;
        padding: 0.95rem 1rem !important;
        vertical-align: middle;
    }
    .btn-action-guindo {
        color: #7B1E2B !important;
        font-weight: 700 !important;
        font-size: 0.88rem !important;
        transition: all 0.2s ease;
    }
    .btn-action-guindo:hover {
        color: #9E2235 !important;
        text-decoration: underline !important;
    }

    /* Lista de Unidades: Fondo blanco dentro de card azul */
    .unit-item-white {
        background-color: #FFFFFF !important;
        color: #0F172A !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 10px !important;
        padding: 0.85rem !important;
        margin-bottom: 0.6rem !important;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.06);
    }
    .unit-item-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(0,0,0,0.12);
        border-color: #CBD5E1 !important;
    }
    .unit-item-white.selected {
        border: 2px solid #7B1E2B !important;
        background-color: #FFF8F8 !important;
        box-shadow: 0 4px 14px rgba(123, 30, 43, 0.25) !important;
    }

    /* Lista de Paradas: Fondo blanco dentro de card azul */
    .stop-item-white {
        background-color: #FFFFFF !important;
        color: #0F172A !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 10px !important;
        padding: 0.75rem !important;
        margin-bottom: 0.5rem !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .stop-item-white.cumplida {
        border-left: 4px solid #10B981 !important;
    }
    .stop-item-white.pendiente {
        border-left: 4px solid #7B1E2B !important;
    }
</style>

<div class="container-fluid px-1 px-md-3">
    <!-- Header Principal -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-guindo px-2 py-1 rounded-2" style="font-size: 0.75rem;">Panel Operativo</span>
                <h2 class="h4 fw-bold mb-0 text-dark">Monitoreo General</h2>
            </div>
            <p class="text-muted small mb-0 mt-1">Control de flota y auditoría de recorridos en tiempo real · Línea 61</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm" style="background-color: #182C4D; border: 1px solid rgba(255,255,255,0.12);">
                <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                <span class="small fw-semibold text-white">GPS Activo (5s)</span>
            </div>
            <a href="{{ route('monitoreo.index') }}" class="btn btn-guindo px-3 py-2 rounded-3 d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-fullscreen"></i>
                <span>Monitoreo Completo</span>
            </a>
        </div>
    </div>

    <!-- 4 Cards de Estadísticas con Paleta Coherente (Navy, Blanco y Guindo) -->
    <div class="row g-3 g-xl-4 mb-4">
        <!-- Card 1: Micros Activos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-card stat-card-custom stat-card-blue">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">Micros en Servicio</div>
                        <div class="display-6 fw-bold mt-1 text-white">{{ $microsActivos ?? 24 }}</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-blue">
                        <i class="bi bi-bus-front"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-10">
                    <span class="badge badge-success-subtle px-2 py-1 rounded-pill small d-inline-flex align-items-center gap-1">
                        <i class="bi bi-circle-fill text-success" style="font-size: 0.45rem;"></i> En ruta
                    </span>
                    <span class="small text-white-50">Flota operativa</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Conductores Disponibles (Acento Guindo) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-card stat-card-custom stat-card-wine">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">Conductores Activos</div>
                        <div class="display-6 fw-bold mt-1 text-white">{{ $conductoresDisponibles ?? 18 }}</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-wine">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-10">
                    <span class="badge badge-guindo-subtle px-2 py-1 rounded-pill small">
                        Personal Asignado
                    </span>
                    <span class="small text-white-50">En turno hoy</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Recorridos Activos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-card stat-card-custom stat-card-cyan">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">Recorridos en Curso</div>
                        <div class="display-6 fw-bold mt-1 text-white">{{ $recorridosActivos ?? 11 }}</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-cyan">
                        <i class="bi bi-map"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-10">
                    <span class="badge badge-blue-subtle px-2 py-1 rounded-pill small">
                        Ida & Vuelta
                    </span>
                    <span class="small text-white-50">Vía GPS</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Micros fuera de servicio (Acento Alerta Guindo) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-card stat-card-custom stat-card-guindo">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">Fuera de Servicio</div>
                        <div class="display-6 fw-bold mt-1 text-white">{{ $microsFueraServicio ?? 3 }}</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-guindo">
                        <i class="bi bi-tools"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-10">
                    <span class="badge badge-guindo px-2 py-1 rounded-pill small text-white">
                        Atención / Taller
                    </span>
                    <span class="small text-white-50">Mantenimiento</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitoreo y Control en Tiempo Real (GPS + Unidades + Paradas) -->
    <div class="row g-3 g-xl-4">
        <!-- Mapa de Seguimiento GPS -->
        <div class="col-12 col-xl-8">
            <div class="dashboard-card">
                <div class="card-header-custom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle p-1 d-flex align-items-center justify-content-center" style="background: rgba(123, 30, 43, 0.4);">
                            <i class="bi bi-pin-map-fill text-white fs-6 px-1"></i>
                        </div>
                        <span class="fw-bold text-white">Mapa de Posicionamiento GPS en Tiempo Real</span>
                    </div>
                    <span id="contadorUnidades" class="badge badge-guindo rounded-pill px-3 py-2 fw-semibold">Cargando unidades...</span>
                </div>
                <div class="p-3">
                    <div id="mapaMonitoreo"></div>
                </div>
            </div>
        </div>

        <!-- Control de Unidades y Paradas en Tiempo Real -->
        <div class="col-12 col-xl-4">
            <!-- Selector de Unidad Activa -->
            <div class="dashboard-card mb-3 mb-xl-4">
                <div class="card-header-custom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bus-front-fill text-info"></i>
                        <span class="fw-bold text-white">Unidades en Ruta</span>
                    </div>
                    <span class="badge badge-blue-subtle rounded-pill px-2 py-1 small">Seleccionar</span>
                </div>
                <div class="p-3" id="listaUnidadesContainer" style="max-height: 235px; overflow-y: auto;">
                    <div class="text-center py-3 text-white-50 small">
                        <span class="spinner-border spinner-border-sm me-2 text-info" role="status"></span>Cargando unidades activas...
                    </div>
                </div>
            </div>

            <!-- Control de Paradas de la Unidad Seleccionada -->
            <div class="dashboard-card">
                <div class="card-header-custom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill" style="color: #F87171;"></i>
                        <span class="fw-bold text-white">Control de Paradas</span>
                    </div>
                    <span id="paradasProgresoBadge" class="badge badge-guindo rounded-pill px-3 py-1 fw-bold">0 / 0</span>
                </div>
                <div class="p-3" id="listaParadasContainer" style="max-height: 235px; overflow-y: auto;">
                    <div class="text-center py-4 text-white-50 small">
                        <i class="bi bi-geo-alt fs-2 d-block mb-1 opacity-50 text-white-50"></i>
                        Selecciona una unidad para auditar el paso por sus paradas.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Estado de Flota (Card Azul, Tabla Interior Blanca, Datos en Negro, Acciones en Guindo) -->
    <div class="dashboard-card p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1 text-white">Estado de la Flota y Recorridos</h5>
                <p class="text-white-50 small mb-0">Resumen operativo de unidades y conductores en servicio</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('control-paradas.index') }}" class="btn btn-sm btn-outline-light px-3 py-1 rounded-2" style="border-color: rgba(255,255,255,0.25);">
                    <i class="bi bi-check2-circle me-1"></i>Auditar Paradas
                </a>
                <a href="{{ route('micro.index') }}" class="btn btn-sm btn-guindo px-3 py-1 rounded-2">
                    <i class="bi bi-bus-front me-1"></i>Ver Micros
                </a>
            </div>
        </div>
        <div class="table-responsive inner-white-container">
            <table class="table table-white-custom align-middle mb-0">
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
                        <td colspan="7" class="text-center py-4 text-muted">Cargando reporte de unidades...</td>
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
            <div class="unit-item-white ${isSelected ? 'selected' : ''}"
                 onclick="seleccionarUnidad(${u.asignacion_id})">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-dark">${u.placa} <span class="badge badge-guindo ms-1">Int. ${u.interno}</span></span>
                    <span class="badge ${u.estado === 'en_curso' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-warning bg-opacity-20 text-dark border border-warning border-opacity-50'} text-uppercase" style="font-size: 0.65rem;">${u.estado}</span>
                </div>
                <div class="small text-secondary text-truncate mb-2"><i class="bi bi-person-fill me-1"></i>${u.conductor}</div>
                <div class="d-flex justify-content-between align-items-center small">
                    <span class="fw-bold text-dark"><i class="bi bi-speedometer2 me-1" style="color: #7B1E2B;"></i>${u.velocidad} km/h</span>
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
            <div class="stop-item-white d-flex align-items-center justify-content-between ${p.cumplida ? 'cumplida' : 'pendiente'}">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge ${p.cumplida ? 'bg-success' : 'badge-guindo'} rounded-circle" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
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
                        : `<span class="badge bg-light text-dark border small" style="color: #7B1E2B !important; border-color: #fca5a5 !important;">Pendiente</span>`
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
                <td><span class="fw-bold text-dark">Micro 201</span> <span class="badge badge-guindo ms-1">Int. 201</span></td>
                <td><span class="text-dark fw-medium">René Morales</span></td>
                <td><span class="text-secondary">Ruta 61 (Ida)</span></td>
                <td><span class="badge rounded-pill px-3 py-1 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 0.75rem;">EN RECORRIDO</span></td>
                <td><span class="fw-bold text-dark">38 km/h</span></td>
                <td><span class="text-muted small">Reciente</span></td>
                <td><a href="{{ route('monitoreo.index') }}" class="btn-action-guindo text-decoration-none d-inline-flex align-items-center gap-1"><i class="bi bi-geo-alt-fill"></i><span>Ver en mapa</span></a></td>
            </tr>
            <tr>
                <td><span class="fw-bold text-dark">Micro 145</span> <span class="badge badge-guindo ms-1">Int. 145</span></td>
                <td><span class="text-dark fw-medium">María Paredes</span></td>
                <td><span class="text-secondary">Ruta 61 (Vuelta)</span></td>
                <td><span class="badge rounded-pill px-3 py-1 bg-warning bg-opacity-20 text-dark border border-warning border-opacity-50" style="font-size: 0.75rem;">EN TERMINAL</span></td>
                <td><span class="fw-bold text-dark">0 km/h</span></td>
                <td><span class="text-muted small">Hace 10m</span></td>
                <td><a href="{{ route('monitoreo.index') }}" class="btn-action-guindo text-decoration-none d-inline-flex align-items-center gap-1"><i class="bi bi-geo-alt-fill"></i><span>Ver en mapa</span></a></td>
            </tr>
        `;
        return;
    }

    let html = '';
    unidadesData.forEach(u => {
        html += `
            <tr>
                <td><span class="fw-bold text-dark">${u.placa}</span> <span class="badge badge-guindo ms-1">Int. ${u.interno}</span></td>
                <td><span class="text-dark fw-medium">${u.conductor}</span></td>
                <td><span class="text-secondary">${u.ruta} (${u.sentido})</span></td>
                <td><span class="badge rounded-pill px-3 py-1 text-uppercase ${u.estado === 'en_curso' ? 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25' : 'bg-warning bg-opacity-20 text-dark border border-warning border-opacity-50'}" style="font-size: 0.75rem;">${u.estado}</span></td>
                <td><span class="fw-bold text-dark">${u.velocidad} km/h</span></td>
                <td><span class="text-muted small">${u.ultima_actualizacion}</span></td>
                <td><button onclick="seleccionarUnidad(${u.asignacion_id})" class="btn btn-sm btn-action-guindo p-0 d-inline-flex align-items-center gap-1"><i class="bi bi-geo-alt-fill"></i><span>Ver en mapa</span></button></td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}
</script>
@endsection
